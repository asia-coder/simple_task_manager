<?php
/**
 * Created by PhpStorm.
 * User: dilshod
 * Date: 2019-05-12
 * Time: 16:48
 */

namespace Task\Models;

use PDO;
use Task\Task;
use Task\TasksCollection;

class TaskModel extends Model
{
    protected $table_name = "tasks";

    protected $table_columns = [
      'id',
      'name',
      'email',
      'task_text',
      'task_status',
    ];

    public const TASK_DOING = 0;
    public const TASK_DONE = 1;

    public const SORT_NAME = "name";
    public const SORT_EMAIL = "email";
    public const SORT_STATUS = "task_status";

    public const ASC = "asc";
    public const DESC = "desc";

    protected $tasksCollection;

    public function getTaskById(int $id)
    {
        $task = $this->getById($id);

        if (empty($task)) {
            return [];
        }

        return (new Task)
                ->setTaskId($task['id'])
                ->setTaskStatus($task['task_status'])
                ->setUserName($task['name'])
                ->setUserEmail($task['email'])
                ->setTaskText($task['task_text']);
    }

    public function setTasksCollection(array $tasks)
    {
        $this->tasksCollection = new TasksCollection();
        foreach ($tasks as $task) {
            $this->tasksCollection->addTask(
                (new Task())
                    ->setTaskId($task['id'])
                    ->setTaskStatus($task['task_status'])
                    ->setUserName($task['name'])
                    ->setUserEmail($task['email'])
                    ->setTaskText($task['task_text'])
            );
        }

        return $this->tasksCollection;
    }

    public function pagination(int $page = 1, int $limit = 3, string $column_sort = 'id', string $order_by = 'desc')
    {
        $offset = ($page - 1) * $limit;
        if ($offset < 0) {
            $offset = 0;
        }

        $prepare_data = [
            ':offset' => $offset,
            ':limit' => $limit
        ];

        $query_sql = "SELECT id, name, email, task_text, task_status 
                            FROM {$this->getTableName()} 
                            ORDER BY {$column_sort} ";

        if (strtolower($order_by) === TaskModel::DESC) {
            $query_sql .= "DESC ";
        } else {
            $query_sql .= "ASC ";
        }

        $query_sql .= "LIMIT :offset, :limit";

        try {
            $prepare = $this->getConnection()->prepare($query_sql);
            $prepare->execute($prepare_data);

            $tasks = $prepare->fetchAll();

            return $this->setTasksCollection($tasks);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}