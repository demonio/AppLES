<?php
/**
 * KumbiaPHP web & app Framework.
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://wiki.kumbiaphp.com/Licencia
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@kumbiaphp.com so we can send you a copy immediately.
 *
 * @category   Kumbia
 *
 * @copyright  2005 - 2016 Kumbia Team (http://www.kumbiaphp.com)
 * @license    http://wiki.kumbiaphp.com/Licencia     New BSD License
 */
namespace Kumbia\ActiveRecord;

/**
 * Implementación de patrón ActiveRecord sin ayudantes de consultas SQL.
 */
abstract class LiteRecord extends BaseRecord
{
    /**
     * Obtener objeto por clave primaria, $var = Modelo($id).
     *
     * @param  string $id valor para clave primaria
     * @return self|false
     */
    public function __invoke($id)
    {
        return self::get($id);
    }

    /**
     * Invoca el callback.
     *
     * @param  string  $callback
     * 
     * @return null|false
     */
    protected function callback(string $callback)
    {
        if (\method_exists($this, $callback)) {
            return $this->$callback();
        }
    }

    /**
     * Crear registro.
     *
     * @param  array           $data
     * 
     * @throws \PDOException
     * @return bool
     */
    public function create(array $data = []): bool
    {
        $this->dump($data);

        // Callback antes de crear
        if ($this->callback('_beforeCreate') === \false) {
            return \false;
        }

        $sql = QueryGenerator::insert($this, $data);

        if ( ! self::prepare($sql)->execute($data)) {
            return \false;
        }

        // Verifica si la PK es autogenerada
        $pk = static::$pk;
        if ( ! isset($this->$pk)) {
            $this->$pk = QueryGenerator::query(
                static::getDriver(),
                'last_insert_id',
                self::dbh(),
                $pk,
                static::getTable(),
                static::getSchema()
            );
        }
        // Callback despues de crear
        $this->callback('_afterCreate');

        return \true;
    }

    /**
     * Actualizar registro.
     *
     * @param  array              $data
     * 
     * @throws \KumbiaException
     * @return bool
     */
    public function update(array $data = []): bool
    {
        $this->dump($data);
        // Callback antes de actualizar
        if ($this->callback('_beforeUpdate') === \false) {
            return \false;
        }
        //$this->hasPK();
        $values = [];
        $sql    = QueryGenerator::update($this, $values);
        //var_dump($values);var_dump($sql);die;
        if ( ! self::prepare($sql)->execute($values)) {
            return \false;
        }
        // Callback despues de actualizar
        $this->callback('_afterUpdate');

        return \true;
    }

    /**
     * Guardar registro.
     *
     * @param  array  $data
     * 
     * @return bool
     */
    public function save(array $data = []): bool
    {
        $this->dump($data);

        if ($this->callback('_beforeSave') === \false) {
            return \false;
        }

        $method = $this->saveMethod();
        $result = $this->$method();

        if ( ! $result) {
            return \false;
        }

        $this->callback('_afterSave');

        return \true;
    }

    /**
     * Retorna el nombre del metodo a llamar durante un save (create o update).
     *
     * @return string
     */
    protected function saveMethod(): string
    {
        return static::hasPK() ? 'create' : 'update';
    }

    /**
     * Eliminar registro por pk.
     *
     * @param  string    $pk valor para clave primaria
     * 
     * @return bool
     */
    public static function delete($pk): bool
    {
        $source  = static::getSource();
        $pkField = static::$pk;
        // use pdo->execute()
        return static::query("DELETE FROM $source WHERE $pkField = ?", [$pk])->rowCount() > 0;
    }

    /**
     * Buscar por clave primaria.
     *
     * @param  string       $pk valor para clave primaria
     * @param  string       $fields campos que se desean obtener separados por coma
     * 
     * @return self|false
     */
    public static function get($pk, $fields = '*')
    {
        $sql = "SELECT $fields FROM ".static::getSource().' WHERE '.static::$pk.' = ?';

        return static::query($sql, [$pk])->fetch();
    }

    /**
     * Obtiene todos los registros de la consulta sql.
     *
     * @param  string       $sql
     * @param  array        $values
     * 
     * @return static[]
     */
    public static function all(string $sql = '', array $values = []): array
    {
        if ( ! $sql) {
            $sql = 'SELECT * FROM '.static::getSource();
        }

        return static::query($sql, $values)->fetchAll();
    }

    /**
     * Obtiene el primer registro de la consulta sql.
     *
     * @param  string       $sql
     * @param  array        $values
     * 
     * @return static|false
     */
    public static function first(string $sql, array $values = [])//: static in php 8
    {
        return static::query($sql, $values)->fetch();
    }
}
