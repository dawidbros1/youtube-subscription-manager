<?php

declare (strict_types = 1);

?>

<div class="d-flex flex-wrap <?=$styles?>">
    <div class = "col-12"><label class="labels"><?=$label?></label></div>

    <div class = "d-flex col-12">
        <span class="input-group-text bg-primary"></span>
            <select class="form-select" name = "<?=$name?>">
                <?php foreach ($options as $item): ?>
                    <option value="<?=$item->$value?>" <?=$selected == $item->$value ? "selected" : ""?>><?=$item->$show?></option>
                <?php endforeach;?>
            </select>
        <span class="input-group-text bg-primary"></span>
    </div>
</div>
