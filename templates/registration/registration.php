<?php

declare (strict_types = 1);

use Phantom\Component\Component;

$regulations = "<a href = " . $route->get('regulations') . ">regulamin</a>";

?>

<div class="mt-sm-5 pt-sm-5">
    <div class="rounded d-flex justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11 col-12 shadow-lg p-5 bg-light">
            <div class="text-center">
                <h3 class="text-primary">Rejestracja</h3>
            </div>
            <div class="p-4">
                <form action="<?=$route->get('registration')?>" method="post">
                    <?php Component::render('form.input', ['mt' => "mt-1", 'type' => "email", 'name' => "email", "placeholder" => "Adres email", 'value' => $email])?>
                    <?php Component::render('error', ['type' => "email", 'names' => ['sanitize', 'validate', 'unique']])?>

                    <?php Component::render('form.input', ['type' => "text", 'name' => "username", "placeholder" => "Nazwa użytkownika", 'value' => $username])?>
                    <?php Component::render('error', ['type' => "username", 'names' => ['between', 'specialCharacters']])?>

                    <?php Component::render('form.input', ['type' => "password", 'name' => "password", "placeholder" => "Hasło"])?>
                    <?php Component::render('error', ['type' => "password", 'names' => ['between']])?>

                    <?php Component::render('form.input', ['type' => "password", 'name' => "repeat_password", "placeholder" => "Powtórz hasło"])?>
                    <?php Component::render('error', ['type' => "password", 'names' => ['same']])?>

                    <?php Component::render('form.checkbox', ['id' => "regulations", 'mt' => "mt-2", 'name' => "regulations", 'label' => "Zapoznałem się i akceptuję $regulations"])?>
                    <?php Component::render('error', ['type' => "regulations", 'names' => ['require']])?>

                    <?php Component::render('form.submit', ['text' => "Utwórz konto", 'mt' => "mt-2"])?>

                    <p class="text-center mt-3">Masz już konto?
                        <a href="<?=$route->get('authorization')?>" class="link">Zaloguj się</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>