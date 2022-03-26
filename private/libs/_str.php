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
	static public function uid()
	{
        return substr(md5(microtime()), 0, 11);
    }
}
