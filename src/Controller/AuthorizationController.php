<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Model\Authorization;
use Phantom\Controller\AbstractController;
use Phantom\Helper\Request;
use Phantom\Helper\Session;
use Phantom\View;

class AuthorizationController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->forGuest();
        $this->model = new Authorization([], true, "User");
    }

    # Method login user
    public function index()
    {
        View::set("Logowanie");

        if ($data = $this->request->isPost(['email', 'password'])) {
            if ($this->model->login($data)) {
                return $this->redirect(self::$config->get('default.route.home'));
            } else {
                if ($this->model->existsEmail($data['email'])) {
                    Session::set("error:password:incorrect", "Wprowadzone hasło jest nieprawidłowe");
                } else {
                    Session::set("error:email:null", "Podany adres email nie istnieje");
                }
                unset($data['password']);
                return $this->redirect('authorization', $data);
            }
        } else {
            return $this->render('authorization/login', [
                'email' => $this->request->getParam('email'),
            ]);
        }
    }
}
