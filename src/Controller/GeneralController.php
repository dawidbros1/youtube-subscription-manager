<?php

declare (strict_types = 1);

namespace App\Controller;

use Phantom\Controller\AbstractController;
use Phantom\View;

class GeneralController extends AbstractController
{
    public function index(): View
    {
        View::set("Strona główna", "home");
        return $this->render('general/home', ['google_login_url' => $this->google->getGoogleLoginUrl()]);
    }
}
