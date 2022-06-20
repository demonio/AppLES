<?php
/**
 */
class Eventos_usuarios extends LiteRecord
{
    #
    public function apuntado($eventos_aid)
    {	
		$vals[] = $eventos_aid;

		$keys[] = '?';
		$vals[] = Session::get('aid');

		$menores = (new Usuarios_menores)->inscritos();
		foreach ($menores as $men) {
			$keys[] = '?';
			$vals[] = $men->aid;
		}
		$keys = implode(', ', $keys);

		$sql = "SELECT * FROM eventos_usuarios WHERE eventos_aid=? AND usuarios_aid IN ($keys)";

		$participantes = parent::all($sql, $vals);

		return parent::arrayBy($participantes, 'usuarios_aid');
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
        return $reservas ?? [];
    }

    #
    public function apuntarse($datos)
    {
		$eventos_aid = $datos['eventos_aid'];

        $evento = (new Eventos)->uno($eventos_aid);
        $apuntados = self::apuntados([$evento]);
        $n_apuntados = empty($apuntados[$eventos_aid])
            ? 0
            : count($apuntados[$eventos_aid]);

		$familia = (new Usuarios_menores)->inscritos();
		$familia[Session::get('aid')] = (new Usuarios)->uno();

		$participantes = empty($datos['participantes'])
			? []
			: (new Usuarios)->grupo($datos['participantes']);

		$en_evento = $en_reserva = [];

		foreach($familia as $fam) {
			# Si estaba apuntado...
			if ( ! empty($apuntados[$eventos_aid][$fam->aid])) {
				# Si ahora no participa...
				if (empty($participantes[$fam->aid])) {
					$this->desapuntarse($eventos_aid, $fam->aid);
				}
				continue;
			}
			# Si no participa...
			if (empty($participantes[$fam->aid])) {
				continue;
			}

            $keys[] = '(?, ?, ?, ?)';

			$vals[] = $eventos_aid;
            $vals[] = $fam->aid;
			$vals[] = date('Y-m-d H:i:s');

			$nombre = $fam->apodo ?: $fam->nombre;

			if ($n_apuntados < $evento->participantes_max) {
				$vals[] = null;
				$en_evento[] = $nombre;
			}
			else {				
				$vals[] = 1;
				$en_reserva[] = $nombre;
				Session::setArray('mensajes', $nombre . _(' apuntado como reserva.'));
			}

			++$n_apuntados;
        }
        if (empty($vals)) {
            return;
        }
		$sql = 'INSERT INTO eventos_usuarios (eventos_aid, usuarios_aid, creado, reserva) VALUES ' . implode(', ', $keys);
		parent::query($sql, $vals);
		
		if (empty($en_evento)) {
			return;
		}

		$evento = (new Eventos)->uno($eventos_aid);
		$organizador = (new Usuarios)->uno($evento->organizador);
		$participante = (new Usuarios)->uno();

		$mensaje[] = _('Plaza confirmada al evento ') . $evento->nombre;

		$mensaje[] = _('El evento comienza a las ') . date('H:i d-m-Y', strtotime($evento->comienza));

		$mensaje[] = _('Y termina a las ') . date('H:i d-m-Y', strtotime($evento->termina));

		$mensaje[] = _('Manténgase en contacto con el organizador de este evento escribiéndole a ') . $organizador->correo;

		$mensaje[] = _('Si no pudiera asistir a tiempo al evento, puede desapuntarse visitando el enlace ') . "https://les.multisitio.es/eventos/ver/$evento->aid";

		$mensaje[] = _('Apuntados: ') . implode(', ', $en_evento);

		$mensaje[] = _('En reserva: ') . implode(', ', $en_reserva);

		$mensaje = implode("\n\n", $mensaje);

        _mail::send($participante->correo, _('Plaza confirmada a ') . $evento->nombre, $mensaje);

		Session::setArray('mensajes', _('Revise su correo.'));
	}

    #
    public function desapuntarse($eventos_aid, $usuarios_aid=null)
    {
		$apuntado = self::apuntado($eventos_aid);
		if ( ! $apuntado) {
			return Session::setArray('mensajes', _('No estabas apuntado.'));
		}
		
		$vals[] = $eventos_aid;
		$vals[] = $usuarios_aid ?: Session::get('aid');

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
		$keys[] = '?';
		$vals[] = Session::get('aid');

		$menores = (new Usuarios_menores)->inscritos();
		foreach ($menores as $men) {
			$keys[] = '?';
			$vals[] = $men->aid;
		}
		$keys = implode(', ', $keys);

		$sql = "SELECT eve.* FROM eventos_usuarios e_u, eventos eve
			WHERE e_u.usuarios_aid IN ($keys) AND e_u.eventos_aid=eve.aid";

		$eventos = parent::all($sql, $vals);

		return parent::arrayBy($eventos);
	}
}
