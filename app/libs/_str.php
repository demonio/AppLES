<?php
/**
 */
class _str
{
    # ID no único
	static public function id($s='')
	{
        return substr(md5($s), 0, 11);
    }

    # ID único
<<<<<<< HEAD
	static public function uid()
	{
        return substr(md5(microtime()), 0, 11);
=======
	static public function uid($s='')
	{
        return substr(md5(microtime().$s), 0, 11);
>>>>>>> parent of b7e76bc... Mostrar contraseña, implementado.
    }
}
