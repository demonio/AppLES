<?php
/**
 */
class Publicaciones extends LiteRecord
{
    #
    public function contarPorUsuarios($usuarios=[])
    {
		if ( ! $usuarios) {
			return [];
		}

		$keys = $vals = [];

		foreach ($usuarios as $usu) {
            $keys[] = '?';
            $vals[] = $usu->aid;
		}

		$keys = implode(', ', $keys);

        $sql = 'SELECT usuarios_aid FROM publicaciones pub WHERE id>0';

		if ($keys) {
 			$sql .= " AND usuarios_aid IN ($keys)";
		}

        $publicaciones = self::all($sql, $vals);

        $resultado = [];
        foreach ($publicaciones as $pub) {
            if (empty($resultado[$pub->usuarios_aid])) {
                $resultado[$pub->usuarios_aid] = 1;
            }
            else {
                ++$resultado[$pub->usuarios_aid];
            }
        }
        return $resultado;
    }
}
