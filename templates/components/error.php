<?php

declare (strict_types = 1);

use Phantom\Helper\Session;

?>

<?php foreach ($params['names'] as $name): ?>
    <?php if ($message = Session::get('error:' . $params['type'] . ':' . $name, true)): ?>
            <div class="text-danger fs-mini error"><?=$message?></div>
    <?php endif;?>
<?php endforeach;?>

