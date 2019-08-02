<?php
namespace Code\DB;

use \PDO;

abstract class Entity
{
    private $conn;

    protected $table;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function findAll($fields = '*'): array
    {
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table;

        $get = $this->conn->query($sql);

        return $get->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id, $fields = '*'): array
    {
        return current($this->where(['id' => $id], '', $fields));
    }

    public function where(array $conditions, $operator = ' AND ', $fields = '*'): array
    {
        $sql = 'SELECT ' .  $fields . ' FROM ' . $this->table . ' WHERE ';

        $binds = array_keys($conditions);

        $where = null;
        foreach ($binds as $value) {
            if (is_null($where)) {
                $where .= $value . ' = :' . $value;
            } else {
                $where .= $operator . $value . ' = :' . $value;
            }
        }

        $sql .= $where;

        $get = $this->bind($sql, $conditions);
        $get->execute();

        return $get->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data): bool
    {
        $binds = array_keys($data);

        $sql = 'INSERT INTO ' . $this->table . '(' . implode(',', $binds) . ', created_at, updated_at) VALUES(:' . implode(', :', $binds) . ', NOW(), NOW())';

        $insert = $this->bind($sql, $data);

        return $insert->execute();
    }

    public function update($data): bool
    {
        if (!array_key_exists('id', $data)) {
            throw new \Exception("É preciso informar um id válido para update!");
        }
        $sql = 'UPDATE ' . $this->table . ' SET ';

        $set = null;
        $binds = array_keys($data);

        foreach ($binds as $value) {
            if ($value !== 'id') {
                $set .= is_null($set) ?  $value . ' = :' . $value : ', ' . $value . ' = :' . $value;
            }
        }

        $sql .= $set . ', updated_at = NOW() WHERE id = :id';

        $update = $this->bind($sql, $data);

        return $update->execute();
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $delete = $this->bind($sql, ['id' => $id]);

        return $delete->execute();
    }

    private function bind($sql, $data)
    {
        $bind = $this->conn->prepare($sql);

         foreach ($data as $k => $v) {
            gettype($v) == 'int' ? $bind->bindValue(':' . $k, $v, PDO::PARAM_INT) : $bind->bindValue(':' . $k, $v, PDO::PARAM_STR);
        }
        return $bind;
    }
}