<?php

namespace App\Controllers;

use App\Framework\Response;
use App\Model\ClientRepository;
use Templates\ClientsView;

class ClientsController
{
    public static function index()
    {
        $response = new Response();
        $response->setContent(ClientsView::render([
                'script' => 'javascript/Clients.js'
            ]
        ));
        return $response;
    }


    public static function editClient()
    {
        $id = $_POST['id'];
        $repository = new ClientRepository();
        $clientName = $_POST['clientName'];
        $client = $repository->findById($id);
        $client->setClientName($clientName);
        $repository->save($client);
        $response = new Response();
        $response->addHeader('Location', 'index.php?action=show-clients');
        return $response;
    }

    public static function deleteClient()
    {
        //TODO: walidacja czy faktycznie $_POST ma to co trzeba
        $id = $_POST['id'];
        $repository = new ClientRepository();
        $repository->deleteById($id);
        $response = new Response();
        $response->addHeader('Location', 'index.php?action=show-clients');

        return $response;
    }

    public static function filterClients()
    {
        $uid = $_SESSION['uid'];
        $name = $_POST['clientName'];
        $clientsRep = new ClientRepository();
        $clients = $clientsRep->filterForUser($uid, $name);
        $response = new Response();
        $response->setContent(ClientsView::render([
            'script' => 'javascript/Clients.js',
            'clients' => $clients,
            'phrase' => $name
        ]));
        return $response;
    }
}