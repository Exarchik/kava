<?php 

class PDODecorator
{
    public $db;

    public function __construct($host, $dbname, $login, $pass)
    {
        $dsn = 'mysql:dbname='.$dbname.';host='.$host;
        try {
            $this->db = new PDO($dsn, $login, $pass);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }

        // главное сразу кодировку включить
        $this->db->query("SET NAMES utf8");
    }

    public function getAll($sql, $type = false)
    {
        $data = array();
        try {
            foreach ($this->db->query($sql, PDO::FETCH_ASSOC) as $value) {
                if ($type == 'one') {
                    return reset($value);
                }
                if ($type == 'row') {
                    return $value;
                }
                if ($type == 'col' || ($type == 'assoc' && count($value) == 1)) {
                    foreach ($value as $val) {
                        $data[] = $val;
                        continue 2;
                    }
                }
                if ($type == 'assoc') {
                    $firstValue = reset($value);
                    $firstKey = key($value);
                    if (count($value) == 2) {
                        $data[$firstValue] = end($value);
                        continue;
                    } else {
                        $data[$firstValue] = array_diff_key($value, array($firstKey => ''));
                        continue;
                    }
                }
                $data[] = $value;
            }
        } catch (PDOException $e) {
            echo 'Ошибка запроса: ' . $e->getMessage();
        }
        return $data;
    }

    public function quoteAllFields($data)
    {
        $result = array();
        foreach ($data as $key => $value) {
            $result[$key] = "`".$value."`";
        }
        return $result;
    }

    public function quoteAll($data)
    {
        $result = array();
        foreach ($data as $key => $value) {
            $result[$key] = $this->quote($value);
        }
        return $result;
    }

    public function getOne($sql)
    {
        return $this->getAll($sql, 'one');
    }

    public function getRow($sql)
    {
        return $this->getAll($sql, 'row');
    }

    public function getCol($sql)
    {
        return $this->getAll($sql, 'col');
    }

    public function getAssoc($sql) 
    {
        return $this->getAll($sql, 'assoc');
    }

    public function __call($methodName, $args)
    {
        return $this->db->$methodName(...$args);
    }
}