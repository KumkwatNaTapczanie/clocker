<?php
namespace App\Service;

use App\Controllers\ChangePasswordController;
use App\Controllers\ClientsController;
use App\Controllers\LoginController;
use App\Controllers\ProfileController;
use App\Controllers\ProjectsController;
use App\Controllers\RegisterController;
use App\Controllers\TasksController;
use App\Controllers\UsersController;
use App\Controllers\ReportsController;
use App\Controllers\AddProjectController;
use App\Controllers\AddTaskController;
use App\Controllers\AddClientController;
use App\Controllers\AddCurrentTaskController;
class Router
{
    public static function resolveRoute($action)
    {
        switch ($action) {
            case 'change-password':
                $controllerName = ChangePasswordController::class;
                $actionName = 'changePassword';
                break;
            case 'delete-client':
                $controllerName = ClientsController::class;
                $actionName = 'deleteClient';
                break;
            case 'delete-project':
                $controllerName = ProjectsController::class;
                $actionName = 'deleteProject';
                break;
            case 'delete-task':
                $controllerName = TasksController::class;
                $actionName = 'deleteTask';
                break;
            case 'delete-user':
                $controllerName = UsersController::class;
                $actionName = 'deleteUser';
                break;
            case 'edit-client':
                $controllerName = ClientsController::class;
                $actionName = 'editClient';
                break;
            case 'edit-profile-except-password':
                $controllerName = ProfileController::class;
                $actionName = 'editProfileExceptPassword';
                break;
            case 'edit-project':
                $controllerName = ProjectsController::class;
                $actionName = 'editProject';
                break;
            case 'edit-task':
                $controllerName = TasksController::class;
                $actionName = 'editTask';
                break;
            case 'edit-user-except-password':
                $controllerName = UsersController::class;
                $actionName = 'editUserExceptPassword';
                break;
            case 'filter-clients':
                $controllerName = ClientsController::class;
                $actionName = 'filterClients';
                break;
            case 'filter-projects':
                $controllerName = ProjectsController::class;
                $actionName = 'filterProjects';
                break;
            case 'filter-tasks':
                $controllerName = TasksController::class;
                $actionName = 'filterTasks';
                break;
            case 'filter-users':
                $controllerName = UsersController::class;
                $actionName = 'filterUsers';
                break;
            case 'login-set':
                $controllerName = LoginController::class;
                $actionName = 'set';
                break;
            case 'login':
                $controllerName = 'App\Controllers\LoginController';
                $actionName = 'index';
                break;
            case 'logout':
                $controllerName = 'App\Controllers\LoginController';
                $actionName = 'logout';
                break;
            case 'password-change-form':
                $controllerName = ChangePasswordController::class;
                $actionName = 'index';
                break;
            case 'register-set':
                $controllerName = RegisterController::class;
                $actionName = 'register';
                break;
            case 'register':
                $controllerName = 'App\Controllers\RegisterController';
                $actionName = 'index';
                break;
            case 'reports-filter':
                $controllerName = ReportsController::class;
                $actionName = 'filterData';
                break;
            case 'show-clients':
                $controllerName = 'App\Controllers\ClientsController';
                $actionName = 'index';
                break;
            case 'show-profile':
                $controllerName = 'App\Controllers\ProfileController';
                $actionName = 'index';
                break;
            case 'show-projects':
                $controllerName = 'App\Controllers\ProjectsController';
                $actionName = 'index';
                break;
            case 'show-tasks':
                $controllerName = 'App\Controllers\TasksController';
                $actionName = 'index';
                break;
            case 'show-users':
                $controllerName = 'App\Controllers\UsersController';
                $actionName = 'index';
                break;
            case 'show-reports':
                $controllerName = 'App\Controllers\ReportsController';
                $actionName = 'index';
                break;
            case 'Show-Add-Project':
                $controllerName='App\Controllers\AddProjectController';
                $actionName='index';
                break;
            case 'Add-Project':
                $controllerName = AddProjectController::class;
                $actionName = 'add_project';
                break;
            case 'Show-Add-Task':
                $controllerName='App\Controllers\AddTaskController';
                $actionName='index';
                break;
            case 'Add-Task':
                $controllerName=AddTaskController::class;
                $actionName='add_task';
                break;
            case 'Show-Add-Client':
                $controllerName='App\Controllers\AddClientController';
                $actionName='index';
                break;
            case 'Add-Client':
                $controllerName=AddClientController::class;
                $actionName='add_client';
                break;
            case 'Add-Current-Task':
                $controllerName=AddCurrentTaskController::class;
                $actionName='add_task';
                break;
            case 'Stop-Task':
                $controllerName=AddCurrentTaskController::class;
                $actionName='stop_task';
                break;
            default:
                $controllerName = 'App\Controllers\FrontpageController';
                $actionName = 'index';
                break;
        }

        return [$controllerName, $actionName];
    }
}