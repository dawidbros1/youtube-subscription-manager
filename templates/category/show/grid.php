<?php

declare (strict_types = 1);

use App\Helper\Data;
use Phantom\Component\Component;

?>

<?php Component::render('category.header', [
    'name' => $category->get('name'),
    'link' => $route->get('category.show', $category->get('id')),
])?>

<div id = "grid" class="d-flex flex-wrap scroll" style="max-height: 2000px;">
   <?php foreach ($videos as $video): ?>
      <div class="col-6 col-sm-6 col-md-4 col-xl-3 col-xxl-2 item mb-2">
         <div class="mx-1">
            <a href = "<?=($baseVideoUrl . $video->id->videoId)?>"><img src = "<?=$video->snippet->thumbnails->medium->url?>"></a>
            <div class="details p-1">
               <div class="mb-2 title"><?=$video->snippet->title?></div>
               <small class="d-block canal"><?=$video->snippet->channelTitle?></small>
               <small class="d-block time"><?=Data::time_elapsed_string($video->snippet->publishedAt)?></small>
            </div>
         </div>
      </div>
   <?php endforeach;?>
</div>

<script>initScroll()</script>