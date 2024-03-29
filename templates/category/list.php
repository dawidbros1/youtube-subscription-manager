<?php use App\Helper\Assets; ?>

<h1 class="text-center border-bottom mb-3 pb-3">Panel zarządzania grupami</h1>

<div id="manage" class="d-flex flex-wrap">
   <?php foreach ($user->getCategories() as $category): ?>
      <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xxl-2 position-relative">
         <a href="<?= $route->get("youtube", $category->getId()) ?>" class="item m-2 fw-bold"><?=
               $category->getName() ?></a>

         <img src="<?= Assets::get("images/edit.png") ?>" class="edit-form-handle icon icon-edit">
         <img src="<?= Assets::get("images/delete.png") ?>" class="delete-form-handle icon icon-delete">
      </div>

      <div class="center form-wrapper delete-form-wrapper d-none">
         <div id="information">Czy jesteś pewny, że chcesz usunąć grupę <span class="fw-bold">
               <?= $category->getName() ?>
            </span></div>

         <form class="d-flex flex-wrap" method="post" action="<?= $route->get('category.delete') ?>">
            <input type="hidden" name="id" value="<?= $category->getId() ?>">
            <button class="col-5 btn btn-success cancel" type="button">Anuluj</button>
            <button class="col-5 offset-2 btn btn-danger" type="submit">TAK</button>
         </form>
      </div>

      <div class="center form-wrapper edit-form-wrapper d-none">
         <h2 class="text-center fw-bold fs-5">EDYCJA GRUPY</h2>

         <form class="d-flex flex-wrap" method="post" action="<?= $route->get('category.edit', $category->getId()) ?>">
            <input class="form-control mb-2" type="text" name="name" value="<?= $category->getName() ?>"
               placeholder="Nazwa grupy">
            <button class="col-5 btn btn-primary cancel" type="button">Anuluj</button>
            <button class="col-5 offset-2 btn btn-success" type="submit">Zapisz zmiany</button>
         </form>
      </div>

   <?php endforeach; ?>
</div>


<script>
   initToggleForm('delete')
   initToggleForm('edit')
</script>