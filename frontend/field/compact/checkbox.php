<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

use noxkiwi\translator\Translator;

/** @var array $data */
$required = '';
$readonly = '';
$checked  = '';
$disabled = '';
if ($data[Field::FIELD_REQUIRED]) {
    $requiredText = Translator::get('CRUD_REQUIRED');
    $required     = <<<HTML
<abbr title="{$requiredText}">(*)</abbr>
HTML;
}
if ($data[Field::FIELD_READONLY]) {
    $readonly = 'readonly';
}
if ((is_bool($data[Field::FIELD_VALUE]) && $data[Field::FIELD_VALUE])) {
    $checked = 'checked';
}
echo <<<HTML
<div class="form-check">
    <input
        type="checkbox"
        class="validate[{$data['field_validator']}] form-check-input"
        value=""
        id="{$data[Field::FIELD_DOMID]}"
        name="{$data[Field::FIELD_NAME]}"
        data-prompt-position="topLeft"
        {$checked}
        {$readonly}
        {$disabled}
    />
    <div
        id="{$data[Field::FIELD_DOMID]} error"
        class="rsCrudValidation"
    ></div>
    <label
        title="{$data[Field::FIELD_DESCRIPTION]}"
        for="{$data[Field::FIELD_DOMID]}"
        class="form-check-label"
    >
        {$data[Field::LABEL]}
        {$required}
  </label>
</div>
HTML;
