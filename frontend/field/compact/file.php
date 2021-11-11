<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;
/** @var array $data */ ?>
<div class="form-group">
    <label
            title="<?= $data['field_description'] ?>"
            for="<?= $data['field_id'] ?>"
            class="control-label col-lg-3">
        <?= $data['field_title'] ?><?php if ($data['field_required']) { ?> <abbr
                title="Dieses Feld muss angegeben werden">(*)</abbr><?php } ?>
    </label>
    <div class="col-lg-9">
        <input
                id="<?= $data['field_id'] ?>"
                name="<?= $data['field_name'] ?>"
                value="<?= $data['value'] ?>"
                type="file"
                class="validate[<?= $data['field_validator'] ?>] form-control <?= $data['field_class'] ?>"
                title="<?= strip_tags($data['field_title']) ?>"
                data-datatype="<?= $data['field_validator'] ?>"
                data-placeholder="<?= $data['field_placeholder'] ?>"
                data-description="<?= $data['field_description'] ?>"
                data-prompt-position="topLeft"
            <?php if ($data['field_readonly']) {
                echo 'readonly';
            } ?>
            <?php if ($data['field_multiple']) {
                echo 'multiple';
            } ?>
        />
    </div>
</div>
