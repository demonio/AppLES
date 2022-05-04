<?php
/**
 */
class Eventos extends LiteRecord
{
    #
    public function crearEvento($data)
    {
		if (strlen($data['nombre']) < 3) {
			return Session::setArray('mensajes', _('Falta el nombre del evento.'));
		}

        foreach(['sistema_propio', 'emitida', 'menores', 'novatos'] as $eti) {
            if (empty($data[$eti])) {
                continue;
            }
            $etiquetas[] = "[$eti]";
        }

        $imagenes = _img::save($_FILES);

		$vals[] = _str::aid();
		$vals[] = $data['nombre'];
		$vals[] = $data['descripcion'];
		$vals[] = implode(', ', $imagenes);
		$vals[] = $data['tipo'] ?: 'partida';
		$vals[] = Session::get('aid');
		$vals[] = $data['apodo'];
		$vals[] = $data['participantes_min'];
		$vals[] = $data['participantes_max'];
		$vals[] = implode(', ', $etiquetas);
		$vals[] = $data['comienza'] ?: null;
		$vals[] = $data['termina'] ?: null;

		$sql = 'INSERT INTO eventos SET aid=?, nombre=?, descripcion=?, imagenes=?, tipo=?, organizador=?, apodo=?, participantes_min=?, participantes_max=?, etiquetas=?, comienza=?, termina=?';
		self::query($sql, $vals);
		
		Session::setArray('mensajes', _('Evento creado, gracias.'));
    }

    #
    public function misEventos()
    {
		$sql = 'SELECT * FROM eventos WHERE organizador=?';
		return self::all($sql, [Session::get('aid')]);
	}
}
