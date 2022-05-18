<?php
/**
 */
class _var
{
	# Retornamos las variables
    static public function return($var='', $no_tags=0) {
        if (is_bool($var)) {
            $var = ($var === TRUE) ? 'TRUE' : 'FALSE';
		}
		if ($no_tags) {
        	$var = str_replace('<', '&lt;', $var);
		}
		return '<hr><pre>' . print_r($var, 1) . '</pre>';
    }
    
    # Imprimimos las variables en pantalla
    static public function echo($var='', $no_tags=0) {
		echo self::return($var, $no_tags);
	}
    
    # Matamos las variables en pantalla
    static public function die($var='', $no_tags=0) {
        echo round((microtime(1) - $_SERVER['REQUEST_TIME_FLOAT'])*1000, 4) . ' ms<hr>';
        echo ($var) ? '<h3>RESULT</h3>' : '<h3>EMPTY</h3>';
        self::echo($var, $no_tags);
        die;
    }
}
