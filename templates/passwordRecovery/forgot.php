<?php

declare (strict_types = 1);

use Phantom\Component\Component;

?>

<div class="mt-sm-5 pt-sm-5">
    <div class="rounded d-flex justify-content-center">
         <div class="col-xl-5 col-lg-6 col-md-8 col-sm-11 col-12 shadow-lg p-5 bg-light">
            <div class="text-center">
                <h3 class="text-primary">Zapomniałem hasła</h3>
            </div>
            <div class="p-4">
                <form action="<?=$route->get('passwordRecovery.forgot')?>" method="post">

                    <?php Component::render('form.input', ['mt' => "mt-1", 'type' => "email", 'name' => "email", "placeholder" => "Adres email"])?>
                    <?php Component::render('error', ['type' => "email", 'names' => ['null']])?>

                    <div class="d-grid col-12 mx-auto mt-3">
                        <button class="btn btn-primary" type="submit"><span></span> Przypomnij hasło </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>