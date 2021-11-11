<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

use function array_key_exists;
use function is_array;

/** @var array $data */
if (! is_array($data['value'])) {
    $data['value'] = [];
}
if (! array_key_exists('country_id', $data['value'])) {
    $data['value']['country_id'] = null;
}
if (! array_key_exists('postalcode', $data['value'])) {
    $data['value']['postalcode'] = null;
}
if (! array_key_exists('city', $data['value'])) {
    $data['value']['city'] = null;
}
if (! array_key_exists('street', $data['value'])) {
    $data['value']['street'] = null;
}
if (! array_key_exists('number', $data['value'])) {
    $data['value']['number'] = null;
}
?>
<input type="hidden" name="<?= $data['field_name'] ?>_" value="1"/>
<input type="hidden" name="<?= $data['field_name'] ?>__COUNTRY_ID" value="1"/>
<?= (new Field(
    [
        'field_type'     => 'input',
        'field_name'     => $data['field_id'] . '__POSTALCODE',
        'field_title'    => Translate::get('DATAFIELD.POSTALCODE'),
        'field_required' => true,
        'field_readonly' => $data['field_readonly'],
        'value'          => $data['value']['postalcode']
    ]
))->output() ?>
<?= (new Field(
    [
        'field_type'     => 'input',
        'field_name'     => $data['field_id'] . '__CITY',
        'field_title'    => Translate::get('DATAFIELD.CITY'),
        'field_required' => true,
        'field_readonly' => $data['field_readonly'],
        'value'          => $data['value']['city']
    ]
))->output() ?>
<?= (new Field(
    [
        'field_type'     => 'input',
        'field_name'     => $data['field_id'] . '__STREET',
        'field_title'    => Translate::get('DATAFIELD.STREET'),
        'field_required' => true,
        'field_readonly' => $data['field_readonly'],
        'value'          => $data['value']['street']
    ]
))->output() ?>
<?= (new Field(
    [
        'field_type'     => 'input',
        'field_name'     => $data['field_id'] . '__NUMBER',
        'field_title'    => Translate::get('DATAFIELD.NUMBER'),
        'field_required' => true,
        'field_readonly' => $data['field_readonly'],
        'value'          => $data['value']['number']
    ]
))->output() ?>
