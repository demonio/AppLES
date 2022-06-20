<?php
//app/libs/lite_record.php

/**
 * Record 
 * Para los que prefieren SQL con las ventajas de ORM
 *
 * Clase padre para añadir tus métodos
 *
 * @category Kumbia
 * @package ActiveRecord
 * @subpackage LiteRecord
 */

use Kumbia\ActiveRecord\LiteRecord as ORM;

class LiteRecord extends ORM
{
	public static function arrayBy($arr_old, $field='aid')
	{
        $arr_new = [];
        foreach ($arr_old as $obj) {
            $arr_new[$obj->$field] = $obj;
        }
		return $arr_new;
	}

	public static function cols()
	{
		$source = static::getSource();
		$rows = self::all("DESCRIBE $source");
		$a = [];
		foreach ($rows as $row) {
			$a[$row->Field] = '';
		}
		return (object)$a;
	}

	public static function count(string $sql='', array $values=[]) : int
    {
        $source = static::getSource();
		$query = $source::query("SELECT COUNT(*) AS count FROM ($sql) AS t", $values)->fetch();
        return (int) $query->count;
	}

	public static function getValue(string $col='')
    {
        $source = static::getSource();
		if ($source == 'usuarios') {
			$sql = "SELECT * FROM $source WHERE aid=?";
		}
		else {
			$sql = "SELECT * FROM $source WHERE usuarios_aid=?";
		}
        $row = static::first($sql, [Session::get('rol')]);
		return $row->$col;
	}

	public static function setValue(string $col='', string $val='')
    {
        $source = static::getSource();
		if ($source == 'usuarios') {
			$sql = "UPDATE $source SET $col=? WHERE aid=?";
		}
		else {
			$sql = "UPDATE $source SET $col=? WHERE usuarios_aid=?";
		}
        static::query($sql, [$val, Session::get('rol')]);
	}

	public static function validate($type, $var)
    {
		if ($type == 'email') {
			$var = filter_var($var, FILTER_VALIDATE_EMAIL);
			$var = mb_strtolower($var);
		}
		return $var;
	}
}
