<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

/** @var \noxkiwi\formbuilder\Form $data */

use noxkiwi\lightsystem\Frontend\Classes;

$formId = $data->getConfig()->get('form_id');
$script = <<<HTML
<script type="module">
import Form from "/?context=resource&file=js%2FForm";

var {$formId}Form = new Form('#{$formId}');
</script>
HTML;

?>
<div class="<?= Classes::FORM ?>">
    <div class="<?= Classes::FORM_RESULT ?>">
    </div>
    <div class="<?= Classes::FORM_CONTAINER ?>">
        <form   id="<?= $data->getConfig()->get('form_id') ?>"
                action="<?= $data->getConfig()->get('form_action', '') ?>"
                class="<?= Classes::FORM_FORM ?> separate-sections validatable form-horizontal"
        ><?php
            foreach ($data->getFieldsets() as $fieldset) {
                echo $fieldset->output();
            }
            foreach ($data->getFields() as $field) {
                echo $field->output();
            } ?>
        </form>
    </div>
</div>
<?= $script ?>
