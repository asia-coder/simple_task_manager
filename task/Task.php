<?php
/**
 * Created by PhpStorm.
 * User: dilshod
 * Date: 2019-05-12
 * Time: 15:07
 */
namespace Task;

use Task\Models\TaskModel;

class Task
{
    /**
     * @var string;
     */
    private $task_id;
    /**
     * @var string;
     */
    private $user_name;

    /**
     * @var string;
     */
    private $user_mail;

    /**
     * @var string;
     */
    private $task_text;

    /**
     * @var string;
     */
    private $task_status;

    /**
     * Task constructor.
     * @set task_id
     */
    public function __construct()
    {
        if (!$this->task_status) {
            $this->task_status = TaskModel::TASK_DOING;
        }
    }

    /**
     * @param string $user_name
     * @return Task
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param string $user_email
     * @return Task
     */
    public function setUserEmail($user_email)
    {
        $this->user_mail = $user_email;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserEmail()
    {
        return $this->user_mail;
    }

    /**
     * @param string $task_text
     * @return Task
     */
    public function setTaskText($task_text)
    {
        $this->task_text = $task_text;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaskText()
    {
        return $this->task_text;
    }

    /**
     * @param string $task_status
     * @return Task
     */
    public function setTaskStatus($task_status)
    {
        $this->task_status = $task_status;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaskStatus()
    {
        return $this->task_status;
    }

    /**
     * @return mixed $task_id
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * @param mixed $task_id
     * @return Task
     */
    public function setTaskId($task_id)
    {
        $this->task_id = $task_id;
        return $this;
    }

    public function toArray()
    {
        return [
            "id" => $this->getTaskId(),
            "status" => $this->getTaskStatus(),
            "name" => $this->getUserName(),
            "email" => $this->getUserEmail(),
            "task_text" => $this->getTaskText()
        ];
    }
}