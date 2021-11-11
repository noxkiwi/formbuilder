<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;
/** @var \noxkiwi\formbuilder\Form $data */
$script = <<<HTML
<script type="module">
    import Form from "/?context=resource&file=js%2FForm";
    let {$data->getConfig()->get('form_id')}Form = new Form('{$data->getConfig()->get('form_id')}');
</script>
HTML;
?>
ASDA
<form enctype="multipart/form-data"
      accept-charset="UTF-8" id="<?= $data->getConfig()->get('form_id') ?>"
      action="<?= $data->getConfig()->get('form_action') ?>"
      method="<?= $data->getConfig()->get('form_method') ?>"
      class="separate-sections validatable form-horizontal">
    <?php
    if (property_exists($data, 'fieldsets')) {
        foreach ($data->getFieldsets() as $fieldset) {
            echo $fieldset->output();
        }
    }
    foreach ($data->getFields() as $field) {
        echo $field->output();
    }
    ?>
</form>

<?= $script ?>
