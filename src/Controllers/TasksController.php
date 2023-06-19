<?php

namespace App\Controllers;

use App\Framework\Response;
use App\Model\ProjectRepository;
use App\Model\TaskRepository;
use Templates\TasksView;

class TasksController
{
    public static function index()
    {
        $response = new Response();
        $response->setContent(TasksView::render([
                'script' => 'javascript/Tasks.js'
            ]
        ));
        return $response;
    }


    public static function editTask()
    {
        //TODO: dokończyć walideację żeby się wyświetlało że projekt nie istnieje
        $id = $_POST['id'];
        $response = new Response();
        $repository = new TaskRepository();
        $projectRep = new ProjectRepository();
        $projectName = $_POST['project'];
        $projectId = null;
        $projects = $projectRep->findByUserId($_SESSION['uid']);
        foreach($projects as $project):
            if ($project->getProjectName()==$projectName){
                $projectId=$project->getId();
            }
        endforeach;
        if ($projectName == '' || !(is_null($projectId))) {
            $title = $_POST['title'];
            $startTime = $_POST['start'];
            $stopTime = $_POST['stop'] ? $_POST['stop'] : null;
            $task = $repository->findById($id);
            $task
                ->setProjectId($projectId)
                ->setTitle($title)
                ->setStartTime($startTime)
                ->setStopTime($stopTime);
            $repository->save($task);
        } else {
            $message = 'Selected project must exist.';
            $response->setContent(TasksView::render([
                'message' => $message
            ]));
            return $response;
        }
        $response->addHeader('Location', 'index.php?action=show-tasks');
        return $response;
    }

    public static function deleteTask()
    {
        //TODO: walidacja czy faktycznie $_POST ma to co trzeba
        $id = $_POST['id'];
        $repository = new TaskRepository();
        $repository->deleteById($id);
        $response = new Response();
        $response->addHeader('Location', 'index.php?action=show-tasks');

        return $response;
    }

    public static function filterTasks()
    {
        $uid = $_SESSION['uid'];
        $name = $_POST['ttitle'];
        $tasksRep = new TaskRepository();
        $tasks = $tasksRep->filterForUser($uid, $name);
        $response = new Response();
        $response->setContent(TasksView::render([
            'script' => 'javascript/Tasks.js',
            'tasks' => $tasks,
            'phrase' => $name
        ]));
        return $response;
    }
}