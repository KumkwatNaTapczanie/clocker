<?php

namespace App\Controllers;

use App\Framework\Response;
use Templates\TasksView;
use App\Model\Project;
use App\Model\UserRepository;
use App\Model\ClientRepository;
use App\Model\Client;
use App\Model\ProjectRepository;
use App\Model\Task;
use App\Model\TaskRepository;

class AddCurrentTaskController
{
    public static function add_task()
    {   
        $uid = $_SESSION['uid'];
        $repository= new TaskRepository();
        $tasks= $repository->findByUserId($uid);
        $flag=0;
        foreach($tasks as $task):
            if($task->getProgress() == 'active'){
                $flag=1;
            }
        endforeach;
        if($flag==0){
            $repository=new TaskRepository();
            $ProjectRep = new ProjectRepository();
            
            $uid = $_SESSION['uid'];
            $project = new Project();
            $Projectname = trim(htmlspecialchars($_POST['Project_Name']));
            $Task_title = trim(htmlspecialchars($_POST['Task-Title']));
            $Project=$ProjectRep->findByUserId($uid);
            $ProjectId=-1;
            date_default_timezone_set("Europe/Warsaw");
            $date = date('Y-m-d H:i:s');
            
            foreach($Project as $project):
                if ($project->getProjectName()==$Projectname){
                    $ProjectId=$project->getId();
                }
            endforeach;

            $task=new Task();
            if(empty($Task_title)){
                $response = new Response();
                $response->setContent(TasksView::render([
                    'values' => [
                        'Project_Name' => $Projectname,
                        'Task-title' => $Task_title,
                    ],
                ]));
                return $response;
            }
            if($ProjectId==-1){
                if(!empty($Projectname)){
                    $response = new Response();
                    $response->setContent(TasksView::render([
                        'values' => [
                            'Project_Name' => $Projectname,
                            'Task-title' => $Task_title,
                        ],
                    ]));
                    return $response;
            }else{
                $task->setUserId($uid);
                $task->setTitle($Task_title);
                $task->setStartTime($date);
                $task->setProgress('active');
                $repository->save($task);

                $response = new Response();
                $response->addHeader('Location', 'index.php?action=show-tasks');
                return $response;
            }
            }
            
            $task->setUserId($uid);
            $task->setProjectId($ProjectId);
            $task->setTitle($Task_title);
            $task->setStartTime($date);
            $task->setProgress('active');
            $repository->save($task);

            $response = new Response();
            $response->addHeader('Location', 'index.php?action=show-tasks');
            return $response;

        }else{
            $response = new Response();
            $response->setContent(TasksView::render());
            return $response;
        }
    }

   

    public static function stop_task()
    {
        $uid = $_SESSION['uid'];
        $repository= new TaskRepository();
        $tasks= $repository->findByUserId($uid);
        $flag=0;
        $Id=-1;
        foreach($tasks as $task):
            if($task->getProgress() == 'active'){
                $flag=1;
                $Id=$task->getId();
            }
        endforeach;
        if($flag==0){
            $response = new Response();
            $response->setContent(TasksView::render());
            return $response;
        }else{
            date_default_timezone_set("Europe/Warsaw");
            $date = date('Y-m-d H:i:s');
            $currentTask=$repository->findById($Id);
            $currentTask->setProgress('inactive');
            $currentTask->setStopTime($date);
            $repository->save($currentTask);
            $response = new Response();
            $response->addHeader('Location', 'index.php?action=show-tasks');
            return $response;
        }   
    }
}
