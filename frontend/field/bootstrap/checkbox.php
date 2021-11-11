<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;
/** @var array $data */ ?>
<div class="form-group">
    <div class="col-lg-9">
        <input
                id="<?= $data['field_id'] ?>"
                name="<?= $data['field_name'] ?>"
                type="checkbox"
                class="validate[<?= $data['field_validator'] ?>] form-control <?= $data['field_class'] ?>"
                title="<?= $data['field_title'] ?>"
                data-prompt-position="topLeft"
            <?php if (is_bool($data['value']) && $data['value']) {
                echo 'checked';
            } ?>
            <?php if ($data['field_readonly']) {
                echo 'readonly';
            } ?>
            <?php if ($data['field_readonly']) {
                echo 'disabled';
            } ?>
        />
        <label
                title="<?= $data['field_description'] ?>"
                for="<?= $data['field_id'] ?>"
                class="control-label col-lg-3">
            <?= $data[Field::LABEL] ?><?php if ($data['field_required']) { ?> <abbr
                    title="Dieses Feld muss angegeben werden">(*)</abbr><?php } ?>
        </label>
        <div id="<?= $data['field_id'] ?>error" class="rsCrudValidation"></div>
    </div>
</div>
