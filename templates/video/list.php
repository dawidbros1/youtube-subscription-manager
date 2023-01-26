<?php

declare(strict_types=1);

use App\Helper\Data;
use Phantom\Component\Component;

?>

<?php Component::render('category.header', [
   'name' => $category->getName(),
   'link' => $route->get('video', $category->getId()),
]) ?>

<div id="list" class="d-flex flex-wrap scroll" style="max-height: 2000px;">
   <?php foreach ($videos as $video): ?>
      <div class="col-12 col-lg-6">
         <div class="item mx-2 mb-3">
            <div class="col-4 col-sm-3">
               <a href="<?=($baseVideoUrl . $video->id->videoId) ?>"><img
                     src="<?= $video->snippet->thumbnails->medium->url ?>"></a>
            </div>

            <div class="col-8 col-sm-9 py-2 ps-3 pe-2 details">
               <div class="fw-bold title">
                  <?= $video->snippet->title ?>
               </div>

               <small class="mb-2 d-block time">
                  <?= $video->snippet->channelTitle ?> /
                  <?= Data::time_elapsed_string($video->snippet->publishedAt) ?>
               </small>

               <div class="descriptions">
                  <small>
                     <?= $video->snippet->description ?>
                  </small>
               </div>
            </div>
         </div>
      </div>
   <?php endforeach; ?>
</div>

<script>initScroll()</script>