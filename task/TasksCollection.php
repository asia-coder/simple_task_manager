<?php
/**
 * Created by PhpStorm.
 * User: dilshod
 * Date: 2019-05-12
 * Time: 15:43
 */

namespace Task;


class TasksCollection
{
    /**
     * @var array
     */
    private $collection;

    /**
     * @param Task $task
     */
    public function addTask(Task $task)
    {
        $this->collection[$task->getTaskId()] = $task;
    }

    /**
     * @param $task_id
     * @return Task
     */
    public function getTaskById($task_id)
    {
        return $this->collection[$task_id];
    }

    /**
     * @return array $collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param array $collection
     */
    public function setCollection(array $collection)
    {
        $this->collection = $collection;
    }
}