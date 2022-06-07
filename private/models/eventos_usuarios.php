<?php
/**
 */
class Eventos_usuarios extends LiteRecord
{
    #
    public function apuntado($eventos_aid)
    {	
		$sql = 'SELECT * FROM eventos_usuarios WHERE eventos_aid=? AND usuarios_aid=?';
		return parent::first($sql, [$eventos_aid, Session::get('aid')]);	
	}

    #
    public function apuntados($eventos)
    {
		if ( ! $eventos) {
			return [];
		}
		$keys = $vals = [];
		foreach ($eventos as $eve) {
            $keys[] = '?';
            $vals[] = $eve->aid;
		}
		$keys = implode(', ', $keys);

        $sql = 'SELECT * FROM eventos_usuarios WHERE reserva IS NULL';
		if ($keys) {
 			$sql .= " AND eventos_aid IN ($keys)";
		}
        $rows = parent::all($sql, $vals);

		$apuntados = [];
        foreach ($rows as $row) {
            $apuntados[$row->eventos_aid][$row->usuarios_aid] = $row;
        }
        return $apuntados;
    }

    #
    public function reservas($eventos)
    {
		if ( ! $eventos) {
			return [];
		}
		$keys = $vals = [];
		foreach ($eventos as $eve) {
            $keys[] = '?';
            $vals[] = $eve->aid;
		}
		$keys = implode(', ', $keys);

        $sql = 'SELECT * FROM eventos_usuarios WHERE reserva=1';
		if ($keys) {
 			$sql .= " AND eventos_aid IN ($keys)";
		}
        $rows = parent::all($sql, $vals);

		$apuntados = [];
        foreach ($rows as $row) {
            $reservas[$row->eventos_aid][$row->usuarios_aid] = $row;
        }
        return $reservas;
    }

    #
    public function apuntarse($eventos_aid)
    {
		$apuntado = self::apuntado($eventos_aid);
		if ($apuntado) {
			return Session::setArray('mensajes', _('Ya estabas apuntado.'));
		}
		
		$vals[] = $eventos_aid;
		$vals[] = Session::get('aid');
		$vals[] = date('Y-m-d H:i:s');

        $evento = (new Eventos)->uno($eventos_aid);
        $apuntados = self::apuntados([$evento]);
        $n_apuntados = empty($apuntados[$eventos_aid])
            ? 0
            : count($apuntados[$eventos_aid]);
		$vals[] = $reserva = ($n_apuntados < $evento->participantes_max) ? null : 1;

		$sql = 'INSERT INTO eventos_usuarios SET eventos_aid=?, usuarios_aid=?, creado=?, reserva=?';
		parent::query($sql, $vals);	

		$evento = (new Eventos)->uno($eventos_aid);
		$organizador = (new Usuarios)->uno($evento->organizador);
		$participante = (new Usuarios)->uno();

		if ($reserva) {
			return Session::setArray('mensajes', _('Apuntado como reserva.'));
		}

		$mensaje[] = _('Plaza confirmada al evento ') . $evento->nombre;

		$mensaje[] = _('El evento comienza a las ') . date('H:i d-m-Y', strtotime($evento->comienza));

		$mensaje[] = _('Y termina a las ') . date('H:i d-m-Y', strtotime($evento->termina));

		$mensaje[] = _('Manténgase en contacto con el organizador de este evento escribiéndole a ') . $organizador->correo;

		$mensaje[] = _('Si no pudiera asistir a tiempo al evento, puede desapuntarse visitando el enlace ') . "https://les.multisitio.es/eventos/ver/$evento->aid";

		$mensaje = implode("\n\n", $mensaje);

        _mail::send($participante->correo, _('Plaza confirmada a ') . $evento->nombre, $mensaje);

		Session::setArray('mensajes', _('Apuntado, revise su correo.'));
	}

    #
    public function desapuntarse($eventos_aid)
    {
		$apuntado = self::apuntado($eventos_aid);
		if ( ! $apuntado) {
			return Session::setArray('mensajes', _('No estabas apuntado.'));
		}
		
		$vals[] = $eventos_aid;
		$vals[] = Session::get('aid');

		$sql = 'DELETE FROM eventos_usuarios WHERE eventos_aid=? AND usuarios_aid=?';
		parent::query($sql, $vals);	

        Session::setArray('mensajes', _('Desapuntado.'));

		$evento = (new Eventos)->uno($eventos_aid);
        $apuntados = self::apuntados([$evento]);
        $n_apuntados = empty($apuntados[$eventos_aid])
            ? 0
            : count($apuntados[$eventos_aid]);

        $reservas = self::reservas([$evento]);
        if ( ! $reservas) {
			return;
		}
		if ($n_apuntados == $evento->participantes_max) {
			return;
		}
		shuffle($reservas[$eventos_aid]);
		$usuarios_aid = array_shift($reservas[$eventos_aid])->usuarios_aid;		
		$sql = 'UPDATE eventos_usuarios SET reserva=? WHERE usuarios_aid=? AND eventos_aid=?';
		parent::query($sql, [null, $usuarios_aid, $eventos_aid]);

		$organizador = (new Usuarios)->uno($evento->organizador);
		$participante = (new Usuarios)->uno($usuarios_aid);

		$mensaje[] = _('Plaza confirmada al evento ') . $evento->nombre;

		$mensaje[] = _('El evento comienza a las ') . date('H:i d-m-Y', strtotime($evento->comienza));

		$mensaje[] = _('Y termina a las ') . date('H:i d-m-Y', strtotime($evento->termina));

		$mensaje[] = _('Manténgase en contacto con el organizador de este evento escribiéndole a ') . $organizador->correo;

		$mensaje[] = _('Si no pudiera asistir a tiempo al evento, puede desapuntarse visitando el enlace ') . "https://les.multisitio.es/eventos/ver/$evento->aid";

		$mensaje = implode("\n\n", $mensaje);

        _mail::send($participante->correo, _('Plaza confirmada a ') . $evento->nombre, $mensaje);
	}

    #
    public function misReservas()
    {
		$sql = 'SELECT eve.* FROM eventos_usuarios e_u, eventos eve
			WHERE e_u.usuarios_aid=? AND e_u.eventos_aid=eve.aid';
		return parent::all($sql, [Session::get('aid')]);	
	}
}
