<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;
/** @var array $data */ ?>
<div class="form-group">
    <label></label>
    <div class="col-lg-9">
        <input
                value="<?= $data['value'] ?>"
                type="submit"
                class="btn btn-success btn-xs pull-right  <?= $data['field_class'] ?>"
                title="<?= strip_tags($data['field_title']) ?>"
        />
    </div>
</div>
