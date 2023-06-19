<?php

namespace App\Controllers;

use App\Framework\Response;
use Templates\AddProjectView;
use App\Model\Project;
use App\Model\UserRepository;
use App\Model\ClientRepository;
use App\Model\Client;
use App\Model\ProjectRepository;

class AddProjectController
{
    public static function index()
    {
        $response = new Response();
        $response->setContent(AddProjectView::render());
        return $response;
    }
    public static function add_project()
    {   
        $repository=new ProjectRepository();
        $ClientRep = new ClientRepository();
        $uid = $_SESSION['uid'];
        $project = new Project();
        $Projectname = trim(htmlspecialchars($_POST['Project-Name']));
        $Wage = trim(htmlspecialchars($_POST['Wage']));
        $Clientname = trim(htmlspecialchars($_POST['Client_Name']));
        $Client=$ClientRep->findByUserId($uid);
        $ClientId=0;
        
        foreach($Client as $client):
            if ($client->getClientName()==$Clientname){
                $ClientId=$client->getId();
            }
        endforeach;
        if( empty($Projectname) || empty($Wage)) {
            $response = new Response();
            $message = 'Please fill all the fields.';
            $response->setContent(AddprojectView::render([
                'message' => $message,
                'values' => [
                    'Project-Name' => $Projectname,
                    'Wage' => $Wage,
                    'Client_Name'=>$Clientname,
                ],
            ]));
            return $response;
        }
        elseif(!is_string($Projectname) ||  $Wage !=floatval($Wage) || $Wage==0){
            $response = new Response();
            $message = 'Please fill all the fields with correct values.';
            $response->setContent(AddprojectView::render([
                'message' => $message,
                'values' => [
                    'Project-Name' => $Projectname,
                    'Wage' => $Wage,
                    'Client_Name'=>$Clientname,
                ],
            ]));
            return $response;
        }elseif( empty($ClientId)){
            if(empty($Clientname)){
                $project->setUserId($uid);
                $project->setProjectName($Projectname);
                $project->setWage($Wage);
                $repository->save($project);

                $response = new Response();
                $response->addHeader('Location', 'index.php?action=show-projects');
                return $response;
            }else{
                $response = new Response();
                $message = 'There is no match for this client.';
                $response->setContent(AddprojectView::render([
                    'message' => $message,
                    'values' => [
                        'Project-Name' => $Projectname,
                        'Wage' => $Wage,
                        'Client_Name'=>$Clientname,
                    ],
                ]));
                return $response;
            }
            
        }
        else{
            $project->setUserId($uid);
            $project->setClientId($ClientId);
            $project->setProjectName($Projectname);
            $project->setWage($Wage);
            $repository->save($project);

            $response = new Response();
            $response->addHeader('Location', 'index.php?action=show-projects');
            return $response;
        }
    }
}