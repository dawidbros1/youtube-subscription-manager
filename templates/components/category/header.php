<?php

use App\Helper\Assets;

?>

<header class="mx-1 mb-2">
   <div><?=$name?></div>
   <div class="icons">
      <a href = "<?=$link . '?flow=grid'?>"><img title = "siatka" src = "<?=Assets::images('grid.png')?>"></a>
      <a href = "<?=$link . '?flow=list'?>" ><img title = "lista" class="me-1" src = "<?=Assets::images('list.png')?>"></a>
   </div>
</header>
