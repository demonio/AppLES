<?php
/**
 */
class Usuarios extends LiteRecord
{
    #
    public function entrar($datos)
    {  
        $sql = 'SELECT aid, clave FROM usuarios WHERE correo=? AND validado=1';
        $usuario = parent::first($sql, [$datos['correo']]);
        if ( ! $usuario) {
            Session::setArray('mensajes', _('Credenciales no aceptados.'));
            return false;
        }

        if ( ! $usuario->clave) {
            if (strlen($datos['clave']) < 12) {
                Session::setArray('mensajes', _('Se requiere una clave de al menos 12 caracteres.'));
                return false;
            }
            $datos['clave'] = password_hash($datos['clave'], PASSWORD_DEFAULT);
            $sql = 'UPDATE usuarios SET clave=? WHERE correo=?';
            parent::query($sql, [$datos['clave'], $datos['correo']]);
        }
        elseif ( ! password_verify($datos['clave'], $usuario->clave)) {
            Session::setArray('mensajes', _('Credenciales no aceptados.'));
            return false;
        }

        Session::set('aid', $usuario->aid);

        Session::setArray('mensajes', _('Bienvenido.'));
    }

    #
    public function borrarse($datos)
    {  
        $sql = 'SELECT correo, clave FROM usuarios WHERE correo=?';
        $usuario = parent::first($sql, [$datos['correo']]);
        if (empty($usuario->correo) ||
        ! password_verify($datos['clave'], $usuario->clave)) {
            Session::setArray('mensajes', _('Credenciales no aceptados.'));
            return false;
        }

        $sql = 'DELETE FROM usuarios WHERE correo=?';
        parent::query($sql, [$datos['correo']]);

        Session::delete('aid');

        Session::setArray('mensajes', _('Usuario eliminado.'));
    }

    #
    public function comprobarSiExisteCorreo($datos)
    {
        $sql = 'SELECT id FROM usuarios WHERE correo=?';
        return parent::first($sql, [$datos['correo']]);
    }

    #
    public function grupo($aids)
    {
        if ( ! $aids) {
            return [];
        }
        foreach ($aids as $aid) {
            $keys[] = 'aid=?';
            $vals[] = $aid;
        }

        $sql = 'SELECT * FROM usuarios WHERE ' . implode(' OR ', $keys);

        $grupo = parent::all($sql, $vals);
        return parent::arrayBy($grupo);
    }

    #
    public function registrar($datos)
    {  
        if (empty($datos['politicas'])) {
            Session::setArray('mensajes', _('Por favor, acepte las políticas.'));
            return false;
        }

        if ($this->comprobarSiExisteCorreo($datos)) {
            Session::setArray('mensajes', _('Credenciales no aceptados.'));
            return false;
        }

        if (strlen($datos['clave']) < 12) {
            Session::setArray('mensajes', _('Se requiere una clave de al menos 12 caracteres.'));
            return false;
        }

        if (strlen($datos['apodo']) < 3) {
            Session::setArray('mensajes', _('Se requiere un apodo de al menos 3 caracteres.'));
            return false;
        }

        if ( ! filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            Session::setArray('mensajes', _('Se requiere un correo valido.'));
            return false;
        }

        $datos['aid'] = _str::aid();

        $datos['clave'] = password_hash($datos['clave'], PASSWORD_DEFAULT);

        $datos['rol'] = 1;

        $datos['validado'] = 0;

        $this->create($datos);

        $mensaje = _("Pulse en el siguiente enlace para confirmar su cuenta en la aplicación:\n\n");

        $mensaje .= 'https://' . $_SERVER['HTTP_HOST'] . '/usuarios/validar/' . base64_encode($datos['clave']);

        _mail::send($datos['correo'], _('Confirme su cuenta en su correo electrónico'), $mensaje);

        Session::setArray('mensajes', _('Acuda a su correo, bandeja de entrada o SPAM.'));
    }

    #
    public function reseteada($clave)
    {
        $clave = base64_decode($clave);
        if ( ! $clave) {
            return;
        }

        $sql = "UPDATE usuarios SET clave=? WHERE clave=?";
        parent::query($sql, [null, $clave]);

        Session::setArray('mensajes', _('Clave reseteada, entre con una nueva.'));
    }

    #
    public function resetear($datos)
    {
        $datos['correo'] = self::validate('correo', $datos['correo']);
        if ( ! $datos['correo']) {
            return Session::setArray('mensajes', t('¿No se deja algo?'));
        }

        $sql = 'SELECT * FROM usuarios WHERE correo=?';
        $usuario = parent::first($sql, [$datos['correo']]);
        if ( ! $usuario) {
            return Session::setArray('mensajes', _('Lo siento, prueba a Crear la cuenta.'));
        }

        $protocol = ($_SERVER["SERVER_PROTOCOL"]=='HTTP/1.1')
            ? 'http://' : 'https://';
        $url = $protocol . $_SERVER['HTTP_HOST'] . "/usuarios/reseteada/" . base64_encode($usuario->clave);

        $body[] = _('Ha solicitado el reseteo de su clave de acceso. Siga instrucciones.');
        
        $body[] = _('Pulse el siguiente enlace y su contraseña quedará en blanco. Después deberá introducir una nueva con un mínimo de 12 caracteres.');

        $body[] = $url;

        $body[] = _('Nos vemos.');

        $to = $datos['correo'];
        $subject = _('Resetear la clave');
        $body = implode("\n\n", $body);

        _mail::send($to, $subject, $body);

        Session::setArray('mensajes', _('Acuda a su cliente de correo.'));
    }

    #
    public function uno($aid=0)
    {
        $aid = $aid ?: Session::get('aid');
        $sql = 'SELECT * FROM usuarios WHERE aid=?';
        return parent::first($sql, [$aid]) ?: parent::cols();
    }

    #
    public function validar($clave)
    {
        $clave = base64_decode($clave);
        $sql = 'SELECT id FROM usuarios WHERE clave=?';
        $usuario = parent::first($sql, [$clave]);
        if ( ! $usuario) {
            Session::setArray('mensajes', _('Credenciales no aceptados.'));
            return false;
        }

        $sql = 'UPDATE usuarios SET validado=1 WHERE clave=?';
        parent::query($sql, [$clave]);

        Session::setArray('mensajes', _('Correo validado. Ahora puede entrar.'));
    }
}
