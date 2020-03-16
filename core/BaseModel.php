<?php

namespace core;

use PDO;
use PDOException;

class BaseModel
{
    protected static $_calledClass;
    public static $tableSchema;

    public function __construct($params = [])
    {
        self::$_calledClass = get_called_class();
        $schema = self::getTableSchema();
        foreach ($schema as $param => $default) {
            $this->$param = Helpers::getValue($params, $param, $default);
        }
    }

    public static function tableName()
    {
        return Helpers::camel2id(Helpers::basename(get_called_class()), '_');
    }

    public static function getTableSchema()
    {
        if (empty(self::$_calledClass)) {
            self::$_calledClass = get_called_class();
        }
        $class = self::$_calledClass;
        if (empty($class::$tableSchema)) {
            $class::$tableSchema = App::$db->loadTableSchema(self::tableName());
        }
        return $class::$tableSchema;
    }

    public function tableSchema()
    {
        return self::getTableSchema();
    }

    private static function convertValue($value)
    {
        if (is_null($value)) {
            return 'NULL';
        } elseif (is_array($value)) {
            return '(' . implode(',', $value) . ')';
        }
        return $value;
    }

    private static function createObject($params)
    {
        if (empty(self::$_calledClass)) {
            self::$_calledClass = get_called_class();
        }
        $class = self::$_calledClass;
        return new $class($params);
    }

    protected static function find($sql)
    {
        $pdo = App::$db->getConnection();
        $query = $pdo->prepare($sql);
        $query->execute();
        $result = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = self::createObject($row);
        }
        $query = null;
        return $result;
    }

    protected static function buildWhere($params = [])
    {
        $where = [];
        foreach ($params as $param => $value) {
            if (is_int($param)) {
                list($param, $operator, $value) = $value;
                $val = self::convertValue($value);
                $where[] = "(`{$param}` $operator {$val})";
            } else {
                $where[] = "(`{$param}` = {$value})";
            }
        }
        return '(' . implode(' and ', $where) . ')';
    }

    public static function findOne($params = [])
    {
        self::$_calledClass = $class = get_called_class();
        $table = self::tableName();
        $where = 'where ' . self::buildWhere($params);
        $query = "select * from {$table} {$where} limit 1";
        $result = self::find($query);
        return array_pop($result);
    }

    public static function findAll($params = [], $limit = -1, $offset = 0)
    {
        self::$_calledClass = $class = get_called_class();
        $table = self::tableName();
        $where = '';
        if (!empty($params)) {
            $where = 'where ' . self::buildWhere($params);
        }
        $lim = '';
        if ($limit > 0) {
            $lim = "limit {$offset}, {$limit}";
        }
        $query = "select * from {$table} {$where} {$lim}";
        return self::find($query);
    }

    /**
     * @param $sql
     * @param array $params
     * @param bool $getId
     * @return bool|string
     */
    public static function execute($sql, $params = [], $getId = false)
    {
        $pdo = App::$db->getConnection();
        $query = $pdo->prepare($sql);
        try {
            $pdo->beginTransaction();
            if (count($params)) {
                foreach ($params as $param => $value) {
                    $query->bindValue($param, $value);
                }
            }
            $query->execute();
            $id = $pdo->lastInsertId();
            $result = $pdo->commit();
            return $getId ? $id : $result;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function save()
    {
        $schema = self::getTableSchema();
        $table = self::tableName();
        $cols = $values = $updates = [];
        foreach ($schema as $colName => $default) {
            $cols[] = $colName;
            $values[":$colName"] = isset($this->$colName) ? $this->$colName : $default;
            $updates[] = "{$colName} = values($colName)";
        }
        $colNames = implode(',', $cols);
        $row = implode(',', array_keys($values));
        $query = "insert into {$table} ($colNames) values ($row) on duplicate key update " . implode(',', $updates);
        $id = self::execute($query, $values, true);
        if ($id !== false && $this->id === null) {
            $this->id = $id;
        }
        return (bool)$id;
    }

    public function delete()
    {
        $table = self::tableName();
        $pk = 'id'; // TODO: add tableSchema class
        $val = $this->$pk;
        $query = "delete from {$table} where {$pk} = {$val}";
        return self::execute($query);
    }
}
