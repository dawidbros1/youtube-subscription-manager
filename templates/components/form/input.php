<?php

declare (strict_types = 1);

?>

<div class = "<?=$styles?>">
    <div class="input-group">
        <?php if (array_key_exists('label', $params)): ?>
            <div class = "col-12"><label class="labels"><?=$label?></label></div>
        <?php endif;?>

        <?php $array = ['type', 'name', 'placeholder', 'disabled', 'value']?>

        <span class="input-group-text bg-primary"></span>
        <input class="form-control"
            <?php foreach ($array as $name): ?>
                <?php if (array_key_exists($name, $params)): ?>
                    <?=$name . "=" . "'" . $params[$name] . "'";?>
                <?php endif;?>
            <?php endforeach;?>
        >
        <span class="input-group-text bg-primary"></span>
    </div>
</div>
