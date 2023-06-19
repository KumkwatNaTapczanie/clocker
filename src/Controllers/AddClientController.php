<?php

namespace App\Controllers;

use App\Framework\Response;
use Templates\AddClientView;
use App\Model\Project;
use App\Model\UserRepository;
use App\Model\ClientRepository;
use App\Model\Client;
use App\Model\ProjectRepository;
use App\Model\Task;
use App\Model\TaskRepository;

class AddClientController
{
    public static function index()
    {
        $response = new Response();
        $response->setContent(AddClientView::render());
        return $response;
    }
    public static function add_client(){
        $repository = new ClientRepository();
        $uid = $_SESSION['uid'];
        $client = new Client();
        $Clientname = trim(htmlspecialchars($_POST['Client-Name']));

        if(empty($Clientname)){
            $response = new Response();
            $message = 'Please fill all the fields with correct values.';
            $response->setContent(AddClientView::render([
                'message' => $message,
                'values' => [
                    'Client_Name'=>$Clientname,
                ],
            ]));
            return $response;
        }

        $client->setUserId($uid);
        $client->setClientName($Clientname);
        $repository->save($client);

        $response = new Response();
        $response->addHeader('Location', 'index.php?action=show-clients');
        return $response;
    }
}