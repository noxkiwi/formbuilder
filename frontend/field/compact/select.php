<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

use noxkiwi\translator\Translator;
use function in_array;
use function is_array;

/** @var array $data */
$required = '';
if ($data['field_required']) {
    $requiredText = Translator::get('CRUD_REQUIRED');
    $required     = <<<HTML
<abbr title="{$requiredText}">(*)</abbr>
HTML;
}
$disabled = '';
if ($data['field_readonly']) {
    $disabled = 'disabled';
}
$multiple = '';
if (($data[Field::FIELD_MULTIPLE] ?? null) === true) {
    $multiple = 'multiple';
}
if (! is_array($data['value'])) {
    $data['value'] = explode(',', (string)($data['value'] ?? ''));
}
$options       = <<<HTML
HTML;
// Show the selected ones first.
if (is_array($data['value'])) {
    foreach ($data['value'] as $preselection) {
        $element = $data[Field::FIELD_ELEMENTS][$preselection] ?? null;
        if ($element === null) {
            continue;
        }
        $options .= <<<HTML
<option value="{$element['value']}" selected>{$element['display']}</option>
HTML;
        unset($data[Field::FIELD_ELEMENTS][$preselection]);
    }
}
// After that show the remaining ones.
foreach ($data[Field::FIELD_ELEMENTS] as $element) {
    $optionSelected = '';
    if ($data['value'] === $element['value'] || $data[Field::FIELD_MULTIPLE] && in_array($element['value'], $data['value'], false)) {
        $optionSelected = 'selected';
    }
    $options .= <<<HTML

<option value="{$element['value']}" {$optionSelected}>{$element['display']}</option>
HTML;
}
echo <<<HTML
<div class="form-group form-group-sm">
    <label title="{$data[Field::FIELD_DESCRIPTION]}" for="{$data[Field::FIELD_DOMID]}" class="control-label control-label-sm">
        {$data[Field::LABEL]}
        {$required}
    </label>
    <select
            id="{$data['field_id']}"
            name="{$data[Field::FIELD_NAME]}"
            class="input-sortable validate[{$data['field_validator']}] {$data['field_class']}"
            title="{$data['field_title']}"
            data-datatype="{$data['field_validator']}"
            data-placeholder="{$data['field_placeholder']}"
            data-description="{$data['field_description']}"
            data-prompt-position="topLeft"
            {$multiple}
            {$disabled}
    >
    {$options}
    </select>
</div>
HTML;
