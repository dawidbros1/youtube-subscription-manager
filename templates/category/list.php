
<div id = "list">
   <div class = "row header">
      <div class="col-6 list-handle border-end"><?=$category->get('name')?></div>
      <div class="col-6 list-handle border-start">Nieskategoryzowane</div>
   </div>
   <!-- SUBSKRYPCJE Z GRUPY -->
   <div class = "list-wrapper">
      <?php foreach ($subscriptionInCategory as $key => $channel): ?>
         <div class="row list-item">
            <img class = "avatar col-3 col-md-2 col-xl-1" src = "<?=$channel->snippet->thumbnails->default->url?>">
             <div class = "details col-7 col-sm-6 col-md-8 col-xl-10">
               <div class="row">
                  <div class="title col-12 fw-bold mb-2"><?=$channel->snippet->title?></div>
                  <div class="col-12"><?=$channel->snippet->shortDescription;?></div>
               </div>
            </div>
            <div class = "col-2 col-sm-3 col-md-2 col-xl-1">
               <form action = "<?=$route->get('channel.delete')?>" method = "post">
                  <input type = "hidden" name = "id" value = "<?=$channels[$key]->get('id')?>">
                  <button class="btn btn-danger" type = "submit">USUŃ</button>
               </form>
            </div>
         </div>

      <?php endforeach;?>
   </div>
   <!-- POZOSTAŁE SUBSKRYPCJE -->
   <div class = "list-wrapper d-none">

   <?php foreach ($allMySubscriptions as $channel): ?>
         <div class="row list-item">
            <img class = "avatar col-3 col-md-2 col-xl-1" src = "<?=$channel->snippet->thumbnails->default->url?>">
             <div class = "details col-7 col-sm-6 col-md-8 col-xl-10">
               <div class="row">
                  <div class="title col-12 fw-bold mb-2"><?=$channel->snippet->title?></div>
                  <div class="col-12"><?=$channel->snippet->shortDescription?></div>
               </div>
            </div>
            <div class = "col-2 col-sm-3 col-md-2 col-xl-1">
               <form action = "<?=$route->get('channel.create')?>" method = "post">
                  <input type = "hidden" name = "channelId" value = "<?=$channel->snippet->resourceId->channelId?>">
                  <input type = "hidden" name = "category_id" value = "<?=$category->get('id')?>">
                  <button class="btn btn-success" type = "submit">DODAJ</button>
               </form>
            </div>
         </div>

      <?php endforeach;?>
   </div>
</div>

<script>initToggleList();</script>