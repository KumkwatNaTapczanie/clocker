<?php

namespace App\Controllers;

use App\Framework\Response;
use App\Model\ClientRepository;
use App\Model\ProjectRepository;
use Templates\ProjectsView;

class ProjectsController
{
    public static function index()
    {
        $response = new Response();
        $response->setContent(ProjectsView::render([
                'script' => 'javascript/Projects.js'
            ]
        ));
        return $response;
    }


    public static function editProject()
    {
        //TODO: zrobić żeby się wyświetlało że nie ma klienta
        $id = $_POST['id'];
        $response = new Response();
        $repository = new ProjectRepository();
        $clientRep = new ClientRepository();
        $clientName = $_POST['client'];
        $clientId = null;
        $clients = $clientRep->findByUserId($_SESSION['uid']);
        foreach($clients as $client):
            if ($client->getClientName()==$clientName){
                $clientId=$client->getId();
            }
        endforeach;
        if ($clientName == '' || !(is_null($clientId))) {
            $projectName = $_POST['projectName'];
            $wage = $_POST['wage'];
            $project = $repository->findById($id);
            $project
                ->setProjectName($projectName)
                ->setClientId($clientId)
                ->setWage($wage);
            $repository->save($project);
        } else {
            $message = 'Selected client must exist.';
            $response->setContent(ProjectsView::render([
                'message' => $message
            ]));
            return $response;
        }
        $response->addHeader('Location', 'index.php?action=show-projects');
        return $response;
    }


    public static function deleteProject()
    {
        //TODO: walidacja czy faktycznie $_POST ma to co trzeba
        $id = $_POST['id'];
        $repository = new ProjectRepository();
        $repository->deleteById($id);
        $response = new Response();
        $response->addHeader('Location', 'index.php?action=show-projects');

        return $response;
    }

    public static function filterProjects()
    {
        $uid = $_SESSION['uid'];
        $name = $_POST['projectName'];
        $projectsRep = new ProjectRepository();
        $projects = $projectsRep->filterForUser($uid, $name);
        $response = new Response();
        $response->setContent(ProjectsView::render([
            'script' => 'javascript/Projects.js',
            'projects' => $projects,
            'phrase' => $name
        ]));
        return $response;
    }
}