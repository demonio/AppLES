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
            $vals[] = $usu->idu;
		}

		$keys = implode(', ', $keys);

        $sql = 'SELECT usuarios_idu FROM publicaciones pub WHERE id>0';

		if ($keys) {
 			$sql .= " AND usuarios_idu IN ($keys)";
		}

        $publicaciones = self::all($sql, $vals);

        $resultado = [];
        foreach ($publicaciones as $pub) {
            if (empty($resultado[$pub->usuarios_idu])) {
                $resultado[$pub->usuarios_idu] = 1;
            }
            else {
                ++$resultado[$pub->usuarios_idu];
            }
        }
        return $resultado;
    }
}
