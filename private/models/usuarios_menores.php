<?php
/**
 */
class Usuarios_menores extends LiteRecord
{
    #
    public function inscribir($datos)
    {  
        if (strlen($datos['nombre']) < 3) {
            Session::setArray('mensajes', _('Se requiere un nombre de al menos 3 caracteres.'));
            return false;
        }  

        if (strlen($datos['edad']) < 0 || strlen($datos['edad']) > 17) {
            Session::setArray('mensajes', _('La edad debe oscilar entre 0 y 17.'));
            return false;
        }

        $vals[] = _str::aid();
        $vals[] = Session::get('aid');
        $vals[] = $datos['nombre'];
        $vals[] = $datos['edad'];

        $sql = 'INSERT INTO usuarios_menores SET aid=?, usuarios_aid=?, nombre=?, edad=?';
        parent::query($sql, $vals);

        Session::setArray('mensajes', _('Menor inscrito correctamente.'));
    }

    #
    public function inscritos()
    {  
        $vals[] = Session::get('aid');
        $sql = 'SELECT * FROM usuarios_menores WHERE usuarios_aid=? ORDER BY nombre';
        return parent::all($sql, $vals);
    }

    #
    public function quitar($aid)
    {  
        $vals[] = $aid;
        $vals[] = Session::get('aid');
        $sql = 'DELETE FROM usuarios_menores WHERE aid=? AND usuarios_aid=?';
        parent::query($sql, $vals);
    }
}
