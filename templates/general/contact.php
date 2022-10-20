<!-- https://bbbootstrap.com/snippets/bootstrap-5-get-touch-contact-form-75878843 -->

<?php

use Phantom\Component\Component;
use Phantom\Helper\Session;

?>

<div id="contact">
    <div class="contact">
        <div class="other">
            <div class="info">
                <h2>Informacje o nas</h2>
                <h3>Adres email</h3>
                <div class = "email"> example@gmail.com </div>

                <h3>Media społecznościowe</h3>

                <div id="icons">
                    <!-- Social media [ links ] -->
                    <a target="_blank" href = "https://www.facebook.com/">
                        <img src = "<?=$path?>facebook.png">
                    </a>

                    <a target="_blank" href = "https://www.youtube.com/">
                        <img src = "<?=$path?>youtube.png">
                    </a>

                    <a target="_blank" href = "https://twitter.com/home">
                        <img src = "<?=$path?>twitter.png">
                    </a>

                    <a target="_blank" href = "https://www.instagram.com/">
                        <img src = "<?=$path?>instagram.png">
                    </a>

                    <a target="_blank" href = "https://www.linkedin.com/feed/">
                        <img src = "<?=$path?>linkedin.png">
                    </a>
                </div>
            </div>
        </div>
        <div class="form">
            <h1>Napisz do nas</h1>
            <form action="<?=$route->get('contact')?>" method = "POST">
                <div class="flex-rev">
                    <input type="text" placeholder="Podaj swoje imię oraz nazwisko" name="name"
                        id="name" />
                    <label for="name">Imię i nazwisko</label>
                </div>

                <div class="flex-rev"> <input type="email" placeholder="example@gmail.com" name="from" id="from" />
                    <label for="from">Adres email</label>
                </div>

                <div class="flex-rev"> <input type="text" placeholder="Temat wiadomości" name="subject" id="subject" />
                    <label for="subject">Temat</label>
                </div>

                <div class="flex-rev">
                    <textarea name="message" placeholder="Powiedz, w jakim celu do nas piszesz" id="message"></textarea>
                    <label for="message">Wiadomość</label>
                </div>


                <div class="g-recaptcha" data-sitekey="<?=$sideKey?>"></div>

                <?php Component::render('error', ['type' => "reCAPTCHA", 'names' => ['robot']])?>

                <button class = "mt-2">Wyślij wiadomość</button>
            </form>
        </div>
    </div>
</div>
