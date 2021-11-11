<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;
/** @var array $data */
?>
<fieldset>
    <legend><?= $data['fieldset_name'] ?></legend>
    <?php foreach ($data['fields'] as $field) { ?>
        <?= $field->output() ?>
    <?php } ?>
</fieldset>
