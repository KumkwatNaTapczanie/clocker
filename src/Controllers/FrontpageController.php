<?php

namespace App\Controllers;

use App\Framework\Response;
use Templates\FrontpageView;

class FrontpageController
{
    public static function index()
    {
        $response = new Response();
        if (isset($_SESSION['uid'])) {
            $response->addHeader('Location', 'index.php?action=show-profile');
        } else {
            $response->setContent(FrontpageView::render());
        }
        return $response;
    }
}