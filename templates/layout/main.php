<html lang="pl">

<head>
    <title><?=$title?></title>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="<?=$location?>/public/css/style.css" rel="stylesheet">

    <?php if ($style): ?>
        <link href="<?=$location?>/public/css/<?=$style?>.css" rel="stylesheet">
    <?php endif;?>

    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="<?=$location?>/public/js/main.js"></script>
</head>

<body>
    <div class="container-fluid">

        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <a class="navbar-brand ms-2 ms-md-3" href="<?=$route->get('home')?>">Strona główna</a>

            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <!-- Trigger -->
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">

            <!-- SET MARGIN WITH PHP -->
            <?php $margin = "ms-2 ms-sm-0"?>
                <!-- ===== LEFT POSITION MENU ===== -->
                <!-- USER MENU -->
                <ul class="navbar-nav me-auto">
                    <?php if ($user): ?>
                        <div class = "d-sm-flex <?=$margin?>">
                            <li class="nav-item"> <a class="nav-link" href="#">Link 1</a></li>
                            <li class="nav-item"> <a class="nav-link" href="#">Link 2</a></li>
                            <li class="nav-item"> <a class="nav-link" href="#">Link 3</a></li>
                        </div>

                        <div class = "d-sm-none border-top"></div>

                        <div class = "d-sm-none <?=$margin?>">
                            <li><a class="nav-link" href="<?=$route->get('user.profile')?>">Profil</a></li>
                            <li><a class="nav-link" href="<?=$route->get('user.logout')?>">Wyloguj</a></li>
                        </div>
                    <?php endif;?>
                </ul>
                <!-- ===== RIGHT POSITION MENU ===== -->
                <ul class="navbar-nav">

                <!-- GUEST MENU -->
                <?php if (!$user): ?>
                    <div class = "d-sm-none border-top"></div>
                    <div class = "d-sm-flex <?=$margin?>">
                        <li class="nav-item"> <a class="nav-link" href="<?=$route->get('registration')?>">Zarejestruj się</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?=$route->get('authorization')?>">Zaloguj się</a></li>
                    </div>
                <?php endif;?>

                <!-- USER MENU DROPDOWN ONLY ON SM+ -->
                <?php if ($user): ?>
                    <ul class="navbar-nav d-none d-sm-block">
                        <!-- USERS -->
                        <li class="nav-item dropdown">
                            <a class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?=$user->username?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?=$route->get('user.profile')?>">Profil</a></li>
                                <li><a class="dropdown-item" href="<?=$route->get('user.logout')?>">Wyloguj</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php endif;?>
                </ul>
            </div>
        </nav>


        <div class="content">
            <?php require_once "templates/messages.php";?>
            <?php require_once "templates/$page.php";?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

   <!-- Remove the container if you want to extend the Footer to full width. -->


   <footer class="bg-dark text-center text-white position-relative w-100 bottom-0">
    <div class="text-center" style="background-color: rgba(0, 0, 0, 0.2);">
        Treść stopki
    </div>
</footer>

</body>

</html>