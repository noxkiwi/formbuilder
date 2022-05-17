<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

use noxkiwi\translator\Translator;

/** @var array $data */
$required = '';
$readonly = '';
if ($data[Field::FIELD_REQUIRED]) {
    $requiredText = Translator::get('CRUD_REQUIRED');
    $required     = <<<HTML
<abbr title="{$requiredText}">(*)</abbr>
HTML;
}
if ($data[Field::FIELD_READONLY]) {
    $readonly = 'readonly';
}
echo <<<HTML
<div id="{$data[Field::FIELD_DOMID]}Pointer" class="form-group form-group-sm">
    <label title="{$data[Field::FIELD_TITLE]}" for="{$data[Field::FIELD_DOMID]}" class="control-label control-label-sm">
        {$data[Field::LABEL]}
        {$required}
    </label>
    <input  name="{$data[Field::FIELD_NAME]}"
            id="{$data[Field::FIELD_DOMID]}"
            value="{$data[Field::FIELD_VALUE]}"
            type="datetime-local"
            class="validate[{$data[Field::FIELD_VALIDATOR]}] form-control form-control-sm"
            title="{$data[Field::FIELD_TITLE]}"
            data-datatype="{$data[Field::FIELD_VALIDATOR]}"
            data-placeholder="{$data[Field::FIELD_PLACEHOLDER]}"
            data-description="{$data[Field::FIELD_DESCRIPTION]}"
            data-prompt-position="topLeft"
            {$readonly}
    />
    <div id="{$data[Field::FIELD_NAME]}error" class="validationFeedback"></div>
</div>
HTML;
