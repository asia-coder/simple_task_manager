<?php
/**
 * Created by PhpStorm.
 * User: dilshod
 * Date: 2019-05-12
 * Time: 18:59
 */

namespace Task\Controllers;


use Klein\ServiceProvider as Service;
use Klein\Request;
use Klein\Response;
use Task\Models\TaskModel;
use Task\Task;

class TaskController extends Controller
{
    public function add(Request $request, Response $response, Service $service)
    {
        $user_name = $service->escape($request->param('user_name'));
        $user_email = $service->escape($request->param('user_email'));
        $task_text = $service->escape($request->param('task_text'));

        $error = false;

        if (empty($user_name)) {
            $error = true;
            $service->flash('Запольните поле: Имя', 'errors');
        }

        if (!empty($user_email)) {

            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                $error = true;
                $service->flash('Некорректный E-mail', 'errors');
            }

        } else {
            $error = true;
            $service->flash('Запольните поле: E-mail', 'errors');
        }

        if (empty($task_text)) {
            $error = true;
            $service->flash('Запольните поле: Текст задачи', 'errors');
        }

        if (!$error) {
            $task = [
                "name" => $user_name,
                "email" => $user_email,
                "task_text" => $task_text,
                "task_status" => TaskModel::TASK_DOING
            ];

            $taskModel = new TaskModel();

            if ($taskModel->insert($task)) {
                $service->flash('Задача добавлена!', 'success');
            }
        }

        $service->back();
    }

    public function editTask(Request $request, Response $response, Service $service)
    {
        if (!isset($_SESSION['admin_name'])) {
            return $response->redirect('/');
        }

        $task_id = (int) $request->param('task_id');

        $taskModel = new TaskModel();
        $task = $taskModel->getTaskById($task_id);

        $service->title = "Редактировать";
        $service->template = "edit_task";

        $service->task_id = $task->getTaskId();
        $service->status = $task->getTaskStatus();
        $service->name = $task->getUserName();
        $service->email = $task->getUserEmail();
        $service->task_text = $task->getTaskText();

        $service->render($this->getTemplatePath());
    }

    public function edit(Request $request, Response $response, Service $service)
    {
        if (!isset($_SESSION['admin_name'])) {
            return $response->redirect('/');
        }

        $task_id = (int) $request->param('task_id');
        $task_text = $service->escape($request->param('task_text'));
        $task_status = (int) $request->param('task_status');

        $task_status = $task_status === TaskModel::TASK_DONE ? TaskModel::TASK_DONE : TaskModel::TASK_DOING;

        $taskModel = new TaskModel();
        $task = $taskModel->getTaskById($task_id);

        if (!empty($task)) {
            $task->setTaskText($task_text);
            $task->setTaskStatus($task_status);

            $taskModel->update([
                'task_text' => $task->getTaskText(),
                'task_status' => $task->getTaskStatus()
            ], $task->getTaskId());
        }

        $response->redirect('/');
    }
}