<?php

declare (strict_types = 1);

?>

JESTEM W SHOW <?=$category->get('name')?>

<div class = "row">
   <?php foreach ($videos as $video): ?>
      <div class="col-12 col-sm-6 col-xl-4 col-xxl-3 my-1">
          <?=$video->player->embedHtml?>
      </div>
   <?php endforeach;?>
</div>