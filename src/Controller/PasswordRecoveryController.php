<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Model\PasswordRecovery;
use App\Model\User;
use Phantom\Controller\AbstractController;
use Phantom\Helper\Request;
use Phantom\Helper\Session;
use Phantom\View;

class PasswordRecoveryController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->forGuest();
        $this->model = new PasswordRecovery([], true, "User");
    }

    # Method sends email to user mail with link to reset password
    public function forgotAction()
    {
        View::set("Przypomnienie hasła");

        if ($email = $this->request->isPost(['email'])) {
            if ($this->model->existsEmail($email)) {
                $username = $this->model->find(['email' => $email], "", false, User::class)->username;
                $this->mail->forgotPassword($email, self::$route->get('passwordRecovery.reset'), $username);
            } else {
                Session::set("error:email:null", "Podany adres email nie istnieje");
            }
            return $this->redirect('passwordRecovery.forgot');
        } else {
            return $this->render('passwordRecovery/forgot');
        }
    }

    # Method updates user password
    public function resetAction()
    {
        View::set("Reset hasła");

        if ($data = $this->request->isPost(['password', 'repeat_password', 'code'])) {
            $this->checkCodeToResetPassword($code = $data['code']); # Check if session code is correct and valid

            if ($user = $this->model->resetPassword($data, $code)) { # Reset password
            return $this->redirect('authorization', ['email' => $user->email]);
            } else {
                return $this->redirect('passwordRecovery.reset', ['code' => $code]);
            }
        }

        if ($code = $this->request->isGet(['code'])) {
            $this->checkCodeToResetPassword($code);
            return $this->render('passwordRecovery/reset', ['email' => Session::get($code), 'code' => $code]);
        } else {
            Session::set('error', 'Kod resetu hasła nie został podany');
            return $this->redirect('passwordRecovery.forgot');
        }
    }

    private function checkCodeToResetPassword($code)
    {
        $names = [$code, "created:" . $code];

        if (Session::hasArray($names)) {
            if ((time() - Session::get("created:" . $code)) > 86400) {
                Session::set('error', 'Link do zresetowania hasła stracił ważność');
                Session::clearArray($names);
                $this->redirect('passwordRecovery.forgot', [], true);
            }
        } else {
            Session::set('error', 'Nieprawidłowy kod resetu hasła');
            $this->redirect('passwordRecovery.forgot', [], true);
        }
    }
}
