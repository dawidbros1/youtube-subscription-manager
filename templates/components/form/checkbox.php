<div class="form-check <?=$styles?>">
    <input class="form-check-input" type="checkbox" id = "<?=$id?>" name = "<?=$name?>"
        <?php if ($params['checked'] ?? null == 1) {echo 'checked';}?>
    >
    <label class="form-check-label" for="<?=$id?>">
        <?=$label?>
    </label>
</div>
