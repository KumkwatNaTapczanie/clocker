<?php

namespace App\Controllers;

use App\Framework\Response;
use Templates\AddTaskView;
use App\Model\Project;
use App\Model\UserRepository;
use App\Model\ClientRepository;
use App\Model\Client;
use App\Model\ProjectRepository;
use App\Model\Task;
use App\Model\TaskRepository;

class AddTaskController
{
    public static function index()
    {
        $response = new Response();
        $response->setContent(AddTaskView::render());
        return $response;
    }

    public static function add_task()
    {
        $repository=new TaskRepository();
        $ProjectRep = new ProjectRepository();
        
        $uid = $_SESSION['uid'];
        $project = new Project();
        $Projectname = trim(htmlspecialchars($_POST['Project_Name']));
        $Task_title = trim(htmlspecialchars($_POST['Task-title']));
        $Time_start = trim(htmlspecialchars($_POST['Time_start']));
        $Time_stop = trim(htmlspecialchars($_POST['Time_stop']));
        $Project=$ProjectRep->findByUserId($uid);
        $ProjectId=-1;
        $date = gmdate('Y-m-d H:i:s');
        
        foreach($Project as $project):
            if ($project->getProjectName()==$Projectname){
                $ProjectId=$project->getId();
            }
        endforeach;

        $task=new Task();
        if(empty($Task_title) || empty($Time_start)){
            $response = new Response();
            $message = 'Please fill all the fiels.';
            $response->setContent(AddTaskView::render([
                'message' => $message,
                'values' => [
                    'Project_Name' => $Projectname,
                    'Task-title' => $Task_title,
                    'Time_start'=>$Time_start,
                    'Time_stop'=>$Time_stop,
                ],
            ]));
            return $response;
        }
        if($Time_start>$date){
            $response = new Response();
            $message = 'Invalid time.';
            $response->setContent(AddTaskView::render([
                'message' => $message,
                'values' => [
                    'Project_Name' => $Projectname,
                    'Task-title' => $Task_title,
                    'Time_start'=>$Time_start,
                    'Time_stop'=>$Time_stop,
                ],
            ]));
            return $response;
        }
        if(!empty($Time_stop)){
            if($Time_stop>$date || $Time_start>$Time_stop){
                $response = new Response();
                $message = 'Invalid time.';
                $response->setContent(AddTaskView::render([
                    'message' => $message,
                    'values' => [
                        'Project_Name' => $Projectname,
                        'Task-title' => $Task_title,
                        'Time_start'=>$Time_start,
                        'Time_stop'=>$Time_stop,
                    ],
                ]));
                return $response;
            }
        }
        if($ProjectId==-1){
            if(!empty($Projectname)){
                $response = new Response();
                $message = 'There is no match for this project.';
                $response->setContent(AddTaskView::render([
                    'message' => $message,
                    'values' => [
                        'Project_Name' => $Projectname,
                        'Task-title' => $Task_title,
                        'Time_start'=>$Time_start,
                        'Time_stop'=>$Time_stop,
                    ],
                ]));
                return $response;
        }else{
            $task->setUserId($uid);
            $task->setTitle($Task_title);
            $task->setStartTime($Time_start);
            $task->setStopTime($Time_stop);
            $task->setProgress('inactive');
            $repository->save($task);

            $response = new Response();
            $response->addHeader('Location', 'index.php?action=show-tasks');
            return $response;
        }
        }
        
        $task->setUserId($uid);
        $task->setProjectId($ProjectId);
        $task->setTitle($Task_title);
        $task->setStartTime($Time_start);
        $task->setStopTime($Time_stop);
        $task->setProgress('inactive');
        $repository->save($task);

        $response = new Response();
        $response->addHeader('Location', 'index.php?action=show-tasks');
        return $response;
        
    }
}
