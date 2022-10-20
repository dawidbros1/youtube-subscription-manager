<?php

declare (strict_types = 1);

use Phantom\Component\Component;

?>

<div class="mt-sm-5 pt-sm-5">
    <div class="rounded d-flex justify-content-center">
         <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11 col-12 shadow-lg p-5 bg-light">
            <div class="text-center">
                <h3 class="text-primary">Logowanie</h3>
            </div>
            <div class="p-4">
                <form action="<?=$route->get('authorization')?>" method="post">
                    <?php Component::render('form.input', ['mt' => "mt-1", 'type' => "email", 'name' => "email", "placeholder" => "Adres email", 'value' => $email])?>
                    <?php Component::render('error', ['type' => "email", 'names' => ['null']])?>

                    <?php Component::render('form.input', ['type' => "password", 'name' => "password", "placeholder" => "Hasło"])?>
                    <?php Component::render('error', ['type' => "password", 'names' => ['incorrect']])?>

                    <?php Component::render('form.submit', ['text' => "Zaloguj się"])?>

                    <div class="text-center mt-3 w-100">
                        <div> Nie masz jeszcze konta?
                            <a href="<?=$route->get('registration')?>" class="link">Zarejestruj się</a>
                        </div>
                        <div> Zapomniałeś hasła?
                            <a href="<?=$route->get('passwordRecovery.forgot')?>" class="link">Przypomnij hasło</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>