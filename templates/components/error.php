<?php

declare (strict_types = 1);

use Phantom\Helper\Session;

?>

<?php foreach ($params['names'] as $name): ?>
    <?php if ($message = Session::getNextClear('error:' . $params['type'] . ':' . $name)): ?>
            <div class="text-danger fs-mini ms-1"><?=$message?></div>
    <?php endif;?>
<?php endforeach;?>

