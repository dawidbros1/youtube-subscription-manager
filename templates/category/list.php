<div id = "list">
   <header class="mb-2 fw-bold"><?=$category->get('name')?></header>
   <div class="row">
      <?php foreach ($channelsFromCategory as $key => $channel): ?>
         <div class="col-4 col-sm-3 col-md-2 col-xl-1 col-xxl-1">
            <form class="position-relative" action = "<?=$route->get('channel.delete')?>" method = "post">
               <input type = "hidden" name = "id" value = "<?=$channels[$key]->get('id')?>">
               <img src = "<?=$channel->snippet->thumbnails->default->url?>">
               <button class="bg-danger" type = "submit">USUŃ</button>
            </form>
         </div>
      <?php endforeach?>
   </div>

   <header class="mb-2 fw-bold">Moje subskrypcje</header>
   <div class="row">
      <?php foreach ($subscriptions as $channel): ?>
         <div class="col-4 col-sm-3 col-md-2 col-xl-1 col-xxl-1">
            <form class="position-relative" action = "<?=$route->get('channel.create')?>" method = "post">
               <input type = "hidden" name = "channelId" value = "<?=$channel->snippet->resourceId->channelId?>">
               <input type = "hidden" name = "category_id" value = "<?=$category->get('id')?>">

               <img src = "<?=$channel->snippet->thumbnails->default->url?>">
               <button title = "<?=$channel->snippet->title?>"  class="bg-success" type = "submit">DODAJ</button>
            </form>
         </div>
      <?php endforeach?>
   </div>
</div>