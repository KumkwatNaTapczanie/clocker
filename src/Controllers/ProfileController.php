<?php
namespace App\Controllers;

use App\Framework\Response;
use App\Model\UserRepository;
use Templates\ProfileView;

class ProfileController
{
    public static function index()
    {
        $response = new Response();
        $response->setContent(ProfileView::render([
                'script' => 'javascript/Profile.js'
            ]
        ));
        return $response;
    }


    public static function editProfileExceptPassword()
    {
        //TODO: walidacja czy faktycznie $_POST ma to co trzeba
        $id = $_POST['id'];
        $repository = new UserRepository();
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $row = [
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'role' => $role
        ];
        $user = UserRepository::userFromRowExceptPassword($row);
        $repository->saveExceptPassword($user);
        $response = new Response();
        $response->addHeader('Location', 'index.php?action=show-profile');
        return $response;
    }

    /*//TODO
    public static function changeProfilePassword()
    {
        $id = $_POST['id'];
        $repository = new UserRepository();
        $oldUser = $repository->findById($id);
        $oldPassword = $oldUser->getPassword();
        $password = $_POST['password'];
        if ($oldPassword != $password) {
            $password = trim(htmlspecialchars($password));
            $password = password_hash($password, PASSWORD_BCRYPT);
        }
    }*/
}
