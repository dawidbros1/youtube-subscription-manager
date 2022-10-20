<?php

declare (strict_types = 1);

use Phantom\Component\Component;
use Phantom\Helper\Session;

?>

<!-- soruce: https://bbbootstrap.com/snippets/bootstrap-5-myprofile-90806631 + moje poprawki -->

<!-- <div class="container rounded bg-white mt-5 mb-5"> -->
<div class="container rounded bg-white my-sm-5">
    <div class="row">
        <!-- Lewa kolumna - Zjęcie użytkownika -->
        <div class="col-md-0 col-lg-1"></div>
        <div class="col-md-12 col-lg-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
            <!-- START -->
                <div class = "w-100 fw-bold px-2 pt-1">
                    <form action = "<?=$route->get('user.update')?>" method = "post" enctype="multipart/form-data">

                        <div class = "position-relative mx-auto mt-5" id = "avatarBox">
                            <img id = "avatar" class="rounded-circle" src="<?=$user->getAvatar(true)?>">
                            <input type = "file" name = "avatar" class = "rounded-circle" id = "file">
                        </div>

                        <?php Component::render('error', ['type' => "file", 'names' => ['empty', 'notImage', 'maxSize', 'types']])?>

                        <div>
                            <div class="fw-bold"> <?=$user->get('username')?></div>
                            <div class="text-black-50"><?=$user->get('email')?></div>
                            <div class = "border-top w-100"></div>
                        </div>

                        <input type = "hidden" name = "update" value = "avatar">

                        <?php Component::render('form.submit', ['text' => "Zmień awatar", 'class' => "profile-button"])?>
                    </form>
                </div>
             <!-- END -->
            </div>
        </div>

        <!-- Środkowa kolumna - Ustawienia profilu -->
        <div class="col-md-12 col-lg-7 border-right">
            <div class="p-3 pt-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Ustawienia profilu</h4>
                </div>

                <!-- Adres email użytkownika -->
                <div class="row mt-3 mb-3">
                    <div class="col-lg-12"><label class="labels">Adres email</label><input type="text" disabled
                            class="form-control" value="<?=$user->get('email')?>"></div>
                </div>

                <!--  Zmiana nazwy użytkownika  -->
                <div class="mb-3 border p-2 pt-1">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom">
                        <h6 class="text-center w-100">Zmień nazwę użytkownika</h6>
                    </div>

                    <form action="<?=$route->get('user.update')?>" method="post">
                        <?php Component::render('form.input', ['class' => "mt-1", 'type' => "text", 'name' => "username", "placeholder" => "Nazwa użytkownika", 'label' => "Nazwa użytkownika", 'value' => $user->get('username')])?>
                        <?php Component::render('error', ['type' => "username", 'names' => ['between', 'specialCharacters']])?>

                        <input type = "hidden" name = "update" value = "username">
                        <?php Component::render('form.submit', ['text' => "Zmień nazwę użytkownika", 'class' => "profile-button"])?>
                    </form>
                </div>

                <!-- Zmiana hasła -->
                <div class="mb-1 border p-2 pt-1">
                    <form action="<?=$route->get('user.update')?>" method="post">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom">
                            <h6 class="text-center w-100">Zmień hasło</h6>
                        </div>

                        <?php Component::render('form.input', ['type' => "password", 'name' => "current_password", "placeholder" => "Aktualne hasło", 'label' => "Aktualne hasło "])?>
                        <?php Component::render('error', ['type' => "current_password", 'names' => ['same']])?>

                        <?php Component::render('form.input', ['mt' => "mt-2", 'type' => "password", 'name' => "password", "placeholder" => "Nowe hasło", 'label' => 'Nowe hasło '])?>
                        <?php Component::render('error', ['type' => "password", 'names' => ['between', 'same']])?>

                        <?php Component::render('form.input', ['mt' => "mt-2", 'type' => "password", 'name' => "repeat_password", "placeholder" => "Powtórz nowe hasło", 'label' => 'Powtórz nowe hasło '])?>
                        <?php Component::render('error', ['type' => "repeat_password", 'names' => ['same']])?>

                        <input type = "hidden" name = "update" value = "password">
                        <?php Component::render('form.submit', ['text' => "Aktualizuj hasło", 'class' => "profile-button"])?>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-0 col-lg-1"></div>
    </div>
</div>