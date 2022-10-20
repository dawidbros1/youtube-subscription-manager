<?php

declare (strict_types = 1);

use Phantom\Component\Component;
use Phantom\Helper\Session;

?>

<div class="mt-sm-5 pt-sm-5">
    <div class="rounded d-flex justify-content-center">
         <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11 col-12 shadow-lg p-5 bg-light">
            <div class="text-center">
                <h3 class="text-primary">Ustaw nowe hasło</h3>
            </div>
            <div class="p-4">
                <form action="<?=$route->get('passwordRecovery.reset')?>" method="post">

                    <?php Component::render('form.input', ['mt' => "mt-1", 'type' => "email", 'name' => "email", 'value' => $email, 'disabled' => 'disabled'])?>

                    <?php Component::render('form.input', ['type' => "password", 'name' => "password", 'placeholder' => "Nowe hasło"])?>
                    <?php Component::render('error', ['type' => "password", 'names' => ['between']])?>

                    <?php Component::render('form.input', ['type' => "password", 'name' => "repeat_password", 'placeholder' => "Powtórz nowe hasło"])?>
                    <?php Component::render('error', ['type' => "password", 'names' => ['same']])?>

                    <input type = "hidden" name = "code" value = "<?=$code?>">

                    <?php Component::render('form.submit', ['text' => "Ustaw nowe hasło"])?>
                </form>
            </div>
        </div>
    </div>
</div>