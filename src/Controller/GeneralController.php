<?php

declare (strict_types = 1);

namespace App\Controller;

use Phantom\Controller\AbstractController;
use Phantom\Helper\Session;
use Phantom\Model\Mail;
use Phantom\View;

class GeneralController extends AbstractController
{
    public function index(): View
    {
        View::set("Strona główna", "home");

        $youtube = $this->googleClient->getYoutubeService();
        // $response = $youtube->subscriptions->listSubscriptions('id', ["mine" => true]);
        // $response = $youtube->listSubscriptions();
        // dump($response);
        // die();

        return $this->render('general/home', ['google_login_url' => $this->googleClient->getGoogleLoginUrl()]);
    }

    public function policyAction(): View
    {
        View::set("Polityka prywatności");
        return $this->render('general/policy');
    }

    public function regulationsAction(): View
    {
        View::set("Regulamin");
        return $this->render('general/regulations');
    }

    # Method sends message to website admin by contact form
    public function contactAction()
    {
        View::set("Strona kontaktowa", "contact");
        $names = ['name', 'from', 'message', 'subject', 'g-recaptcha-response'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $secret = self::$config->get('reCAPTCHA.key.secret');
            $response = null;
            $reCaptcha = new \ReCaptcha($secret);

            $data = $this->request->postParams($names);

            $response = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"],
                $data['g-recaptcha-response']
            );

            if ($response != null && $response->success) {
                $this->mail->contact($data);
            } else {
                Session::set('error:reCAPTCHA:robot', "Robotów nie wpuszczamy");
            }

            return $this->redirect('contact');
        }

        return $this->render('general/contact', [
            'path' => self::$config->get('default.path.medium') ?? "",
            'sideKey' => self::$config->get('reCAPTCHA.key.side'),
        ]);
    }
}
