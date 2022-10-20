<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Helper\Session;

class Mail extends AbstractModel
{
    private $mail;
    public function __construct()
    {
        $this->mail = self::$config->get('mail');
    }

    # Method sends email by contact form
    public function contact(array $data)
    {
        $headers = "From: " . strip_tags($data['from']) . "\r\n";
        $headers .= "Reply-To: " . strip_tags($data['from']) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $data['name'] = htmlentities($data['name']);
        $data['message'] = htmlentities($data['message']);

        $html = "<html> <head> </head> <body> <p>Imię i nazwisko: " . $data['name'] . " </p> " . $data['message'] . " </body> </html>";

        if ($this->send($this->mail['email'], $data['subject'], $html, $headers)) {
            Session::success("Wiadomość została wysłana");
        }
    }

    # Method sends email by forgotPassword form
    public function forgotPassword($email, $route, $username)
    {
        $location = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $code = rand(1, 1000000) . "_" . date('Y-m-d H:i:s');
        $hash = $this->hash($code, 'md5');

        Session::set($hash, $email);
        Session::set('created:' . $hash, time());

        $data = [];
        $data['email'] = $email;
        $data['link'] = $location . $route . "&code=$hash";
        $data['subject'] = $_SERVER['HTTP_HOST'] . " - Reset hasła";
        $data['username'] = $username;

        // === /
        $headers = "From: " . strip_tags($this->mail['email']) . "\r\n";
        $headers .= "Reply-To: " . strip_tags($this->mail['email']) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $message = "";
        $message .= "Witaj " . $data['username'] . ", <br><br>";
        $message .= "<strong>Otrzymaliśmy prośbę o zmianę hasła do Twojego konta</strong><br><br>";
        $message .= "Jeśli nie zażądałeś tej zmiany, możesz zignorować tę wiadomość.<br><br>";
        $message .= "Aby ustawić nowe hasło, kliknij w poniższy link: <br>";
        $message .= '<a href = "' . $data['link'] . '">' . $data['link'] . '</a> <br><br>';
        $message .= "Link wygaśnie za 24 godziny<br><br>";
        $message .= "Wiadomość została wysłana automatycznie, prosimy na nią nie odpowiadać.<br><br>";
        $message .= "Pozdrawiam";

        $html = "<html><head></head><body>" . $message . "</body></html>";

        if ($this->send($data['email'], $data['subject'], $html, $headers)) {
            Session::success("Link do zresetowania hasła został wysłany na podany adres email");
        }
    }

    # Method to send email
    private function send($email, $subject, $html, $headers)
    {
        // $this->showMessage($html);

        if (mail($email, $subject, $html, $headers)) {
            return true;
        } else {
            Session::set('error', "Wystąpił problem podczas wysyłania wiadomości, prosimy spróbować później");
            return false;
        }
    }

    # Method was created for test to look on email which will be sent
    private function showMessage($html)
    {
        dump($html);
        die();
    }
}
