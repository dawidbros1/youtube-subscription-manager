<?php

declare (strict_types = 1);

namespace App\Controller;

use Phantom\Controller\AbstractController;
use Phantom\Helper\Session;

class AuthorizationController extends AbstractController
{
    # Back from google_login_url => save access_token to session
    # Next redirect to main page [ project.location ]
    public function saveAccessTokenAction()
    {
        header('Location: ' . filter_var(self::$config->get('project.location'), FILTER_SANITIZE_URL));
        $client = $this->getClient();
        $client->authenticate($_GET['code']);
        Session::set('access_token', $client->getAccessToken());
    }

    public function logoutAction()
    {
        $this->googleClient->logout();
        return $this->redirect('home');
    }

}
