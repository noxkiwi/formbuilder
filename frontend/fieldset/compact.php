<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;
use noxkiwi\core\Datacontainer;

$data ??= new Datacontainer([]);
?>
<div class="col-md-6">
    <fieldset>
        <legend><?= $data['fieldset_name'] ?></legend>
        <?php
        /** @var \noxkiwi\formbuilder\Field $field */
        foreach ($data['fields'] as $field) {
            echo $field->output();
        } ?>
    </fieldset>
</div>
