<?php
/**
 */
class Eventos extends LiteRecord
{
    #
    public function aceptados()
    {
		$sql = 'SELECT * FROM eventos WHERE aceptado IS NOT NULL';
		return parent::all($sql);
	}

    #
    public function aceptar($aid)
    {
		$vals[] = date('Y-m-d H:i:s');
		$vals[] = Session::get('aid');
		$vals[] = $aid;

		$sql = 'UPDATE eventos SET aceptado=?, aceptado_por=? WHERE aid=?';
		return parent::query($sql, $vals);
    }

    #
    public function porAceptar()
    {
		$sql = 'SELECT * FROM eventos WHERE aceptado IS NULL';
		return parent::all($sql);
	}

    #
    public function borrarEvento($data)
    {
		$sql = 'DELETE FROM eventos WHERE organizador=? AND aid=?';
		return parent::query($sql, [Session::get('aid'), $data['aid']]);
	}

    #
    public function miEvento($aid)
    {
		if ( ! $aid) {
			return parent::cols();
		}
		$sql = 'SELECT * FROM eventos WHERE organizador=? AND aid=?';
		return parent::first($sql, [Session::get('aid'), $aid]);
	}

    #
    public function misEventos()
    {
		$sql = 'SELECT * FROM eventos WHERE organizador=?';
		return parent::all($sql, [Session::get('aid')]);
	}

    #
    public function salvarEvento($data)
    {
		if (strlen($data['nombre']) < 3) {
			return Session::setArray('mensajes', _('Falta el nombre del evento.'));
		}

        $imagenes = ! empty($_FILES['imagenes']['name'][0])
			? _img::save($_FILES)
			: $data['imagenes_guardadas'];

		if (empty($data['aid'])) {
			$vals[] = _str::aid();
		}
		$vals[] = $data['nombre'];
		$vals[] = $data['descripcion'];
		$vals[] = implode(', ', $imagenes);
		$vals[] = $data['tipo'] ?: 'partida';
		$vals[] = $data['sistema'];
		if (empty($data['aid'])) {
			$vals[] = Session::get('aid');
		}
		$vals[] = $data['apodo'];
		$vals[] = $data['participantes_min'];
		$vals[] = $data['participantes_max'];
		$vals[] = empty($data['etiquetas'])
			? '' 
			: '[' . implode('], [', $data['etiquetas']) . ']';
		$vals[] = $data['comienza'] ?: null;
		$vals[] = $data['termina'] ?: null;

		if (empty($data['aid'])) {
			$sql = 'INSERT INTO eventos SET aid=?, nombre=?, descripcion=?, imagenes=?, tipo=?, sistema=?, organizador=?, apodo=?, participantes_min=?, participantes_max=?, etiquetas=?, comienza=?, termina=?';
			Session::setArray('mensajes', _('Evento creado, gracias.'));
		}
		else {
			$vals[] = $data['aid'];
			$vals[] = Session::get('aid');
			$sql = 'UPDATE eventos SET nombre=?, descripcion=?, imagenes=?, tipo=?, sistema=?, apodo=?, participantes_min=?, participantes_max=?, etiquetas=?, comienza=?, termina=? WHERE aid=? AND organizador=?';
			Session::setArray('mensajes', _('Evento editado.'));
		}
		parent::query($sql, $vals);
    }

    #
    public function aceptado($aid)
    {
		if ( ! $aid) {
			return parent::cols();
		}
		$sql = 'SELECT * FROM eventos WHERE aid=? AND aceptado IS NOT NULL';
		return parent::first($sql, [$aid]);
	}

    #
    public function uno($aid)
    {
		if ( ! $aid) {
			return parent::cols();
		}
		$sql = 'SELECT * FROM eventos WHERE aid=?';
		return parent::first($sql, [$aid]);
	}
}
