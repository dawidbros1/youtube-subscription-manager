<?php

use App\Helper\Assets;
use Phantom\Component\Component;

?>

<html lang="pl">

<head>
   <title>
      <?= $title ?>
   </title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <link href="<?= Assets::css('style.css') ?>" rel="stylesheet">
   <link href="<?= Assets::css('sidebar.css') ?>" rel="stylesheet">
   <link href="<?= Assets::css('category.css') ?>" rel="stylesheet">

   <?php if ($style): ?>
      <link href="<?= $location ?>/public/css/<?= $style ?>.css" rel="stylesheet">
   <?php endif; ?>

   <script src='https://www.google.com/recaptcha/api.js'></script>
   <script src="<?= Assets::js('main.js') ?>"></script>
   <script src="<?= Assets::js('scroll.js') ?>"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
   <div class="container-fluid">
      <div id="wrapper" class="d-flex">
         <ul id="sidebar-nav">
            <li class="sidebar-brand text-white">Nazwa aplikacji</li>
            <li><a href="<?= $route->get('home') ?>">Strona główna</a></li>

            <?php if ($user): ?>
               <li><a href="<?= $route->get('category.list') ?>">Zarządzaj grupami</a></li>
               <div class="group">
                  <div class="title">Moje grupy</div>

                  <?php foreach ($user->getCategories() ?? [] as $item): ?>
                     <li>
                        <a href="<?= $route->get('video', [$item->getId()]) ?>"><?= $item->getName() ?></a>
                     </li>
                  <?php endforeach; ?>
               </div>

               <div class="group">
                  <form id="create-category-form" action="<?= $route->get('category.create') ?>" method="post">
                     <div class="d-flex flex-wrap">
                        <input class="col-11 col-sm-10" name="name" type="text" placeholder="Nazwa grupy">
                        <button class="col-1 col-sm-2 btn btn-primary fw-bold" type="submit">+</button>
                     </div>
                  </form>

                  <?php Component::render('error', ['type' => "name", 'names' => ['between']]) ?>
               </div>

               <div class="group">
                  <li><a href="<?= $route->get('authorization.logout') ?>">Wyloguj</a></li>
               </div>
            <?php endif; ?>

            <div id="menu-toggle">
               <img id="hamburger" src="<?= Assets::images('hamburger.png') ?>">
            </div>
         </ul>

         <!-- /#sidebar-wrapper -->

         <!-- Page Content -->
         <div id="page-content-wrapper">
            <?php require_once 'templates/messages.php'; ?>
            <?php require_once "templates/$page.php"; ?>

            <!-- /#wrapper -->

            <!-- Menu Toggle Script -->
            <script>
               $("#menu-toggle").click(function (e) {
                  e.preventDefault();
                  $("#sidebar-nav").toggleClass("active");
                  // $("#page-content-wrapper").toggleClass("show");
               });

               // 576 is max width from css media
               if ($(window).width() <= 576) {
                  $("#hamburger").click();
               }

            </script>
         </div>
      </div>
   </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
      </script>
</body>

</html>