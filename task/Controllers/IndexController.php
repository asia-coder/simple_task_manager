<?php
/**
 * Created by PhpStorm.
 * User: dilshod
 * Date: 2019-05-12
 * Time: 14:43
 */

namespace Task\Controllers;

use Klein\ServiceProvider as Service;
use Klein\Request;
use Klein\Response;
use Task\Models\TaskModel;

class IndexController extends Controller
{
    /**
     * @var int
     */
    protected const COUNT_ITEMS_PAGE = 3;

    public function index(Request $request, Response $response, Service $service)
    {
        $page_param = (int) $request->param('page');
        $sort_param = strtolower($service->escape($request->param('sort')));
        list($sort_name, $order_sort) = explode('|', $sort_param);

        $taskModel = new TaskModel();
        $tasksCount = $taskModel->count();
        $pageCount = ceil($tasksCount / IndexController::COUNT_ITEMS_PAGE);

        if ($page_param > $pageCount) {
            $service->title = "Not Found";
            $service->template = "not_found";
            $service->render($this->getTemplatePath());
            return;
        }

        switch ($sort_name) {
            case TaskModel::SORT_NAME:
            case TaskModel::SORT_EMAIL:
            case TaskModel::SORT_STATUS:
                $taskList = $taskModel
                                  ->pagination(
                                      $page_param,
                                      IndexController::COUNT_ITEMS_PAGE,
                                      $sort_name,
                                      ($order_sort ?? TaskModel::ASC));
                break;
            default:
                $taskList = $taskModel->pagination($page_param, IndexController::COUNT_ITEMS_PAGE);
        }

        $tasks = [];
        foreach ($taskList->getCollection() as $task) {
            $tasks[] = $task->toArray();
        }

        $service->title = "Список задач";
        $service->tasks = $tasks;
        $service->sortNames = [
            TaskModel::SORT_NAME => "Имя",
            TaskModel::SORT_EMAIL => "Email",
            TaskModel::SORT_STATUS => "Статус"
        ];

        if ($sort_name && $order_sort) {
            $service->sort = ['sort_name' => $sort_name, 'order' => $order_sort];
        }

        if (isset($_COOKIE['login']) && $_COOKIE['login'] === 'auth') {
            if (!isset($_SESSION['admin_name'])) {
                $_SESSION['admin_name'] = 'admin';
            }

            $service->user = $_SESSION['admin_name'];
        }

        $service->current_page = $page_param;
        $service->pages = $pageCount;
        $service->errors = $service->flashes('errors');
        $service->success = $service->flashes('success')[0] ?? null;
        $service->template = "index";
        $service->render($this->getTemplatePath());
    }
}