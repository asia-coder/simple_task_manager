<?php
/**
 * Created by PhpStorm.
 * User: dilshod
 * Date: 2019-05-12
 * Time: 16:53
 */

namespace Task\Models;

use PDO;

abstract class Model
{
    protected $connection;

    /**
     * @var string;
     */
    protected $database = "task_manager";

    /**
     * @var string;
     */
    protected $table_name;

    /**
     * @var string
     */
    protected $primary_key_column = 'id';

    /**
     * @var array
     */
    protected $table_columns = [];

    public function __construct()
    {
        $this->connector();
    }

    protected function getConnection()
    {
        if (!$this->connection) {
            $this->connector();
        }

        return $this->connection;
    }

    protected function connector()
    {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $this->connection = new PDO("mysql:host=localhost;dbname={$this->database};charset=utf8mb4", 'root', '1', $options);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $query_sql = "SELECT id, name, email, task_text, task_status FROM {$this->getTableName()}";
        $prepare = $this->getConnection()->prepare($query_sql);
        $prepare->execute();

        return $prepare->fetchAll() ?? [];
    }

    /**
     * @return int
     */
    public function count()
    {
        $query_sql = "SELECT COUNT(id) FROM {$this->getTableName()}";
        $query = $this->getConnection()->query($query_sql);
        $query->execute();

        $count = $query->fetchColumn();

        return $count;
    }

    /**
     * @param $id
     * @return array
     */
    public function getById(int $id)
    {
        $columns = implode(',', $this->getTableColumns());

        $select_sql = "SELECT {$columns} FROM {$this->getTableName()} WHERE {$this->primary_key_column} = :id";

        $prepare = $this->getConnection()->prepare($select_sql);
        $prepare->execute([':id' => $id]);

        return $prepare->fetch() ?? [];
    }

    public function insert(array $data)
    {
        $columns_names = $this->getColumnsName($data);
        $columns_data = $this->getColumnsReplaceKeys($data);
        $columns_prepare_names = implode(',', array_keys($columns_data));

        $insert_sql = "INSERT INTO {$this->getTableName()} ({$columns_names}) VALUES ({$columns_prepare_names})";
        $prepare = $this->getConnection()->prepare($insert_sql);

        return $prepare->execute($columns_data);
    }

    public function update(array $data, int $task_id)
    {
        $columns_names = implode(',', $this->getUpdateColumnsReplaceMark($data));

        $update_sql = "UPDATE {$this->getTableName()} SET {$columns_names} WHERE {$this->primary_key_column} = {$task_id}";
        $prepare = $this->getConnection()->prepare($update_sql);

        return $prepare->execute(array_values($data));
    }

    protected function getColumnsName(array $data)
    {
        return implode(',', array_keys($data));
    }

    protected function getUpdateColumnsReplaceMark(array $data)
    {
        $columns_prepare = [];
        foreach ($data as $key => $column) {
            $columns_prepare[$key] = $key . " = " . "?";
        }

        return $columns_prepare;
    }

    protected function getColumnsReplaceKeys(array $data)
    {
        $columns = [];
        foreach ($data as $key => $column) {
            $columns[':' . $key] = $column;
        }

        return $columns;
    }

    protected function getTableColumns()
    {
        return $this->table_columns;
    }

    protected function getTableName()
    {
        return $this->table_name;
    }
}