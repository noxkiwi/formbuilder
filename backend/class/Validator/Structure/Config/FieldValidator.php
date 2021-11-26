<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder\Validator\Structure\Config;

use noxkiwi\validator\Validator\Structure\ConfigValidator;

/**
 * I am
 *
 * @package      noxkiwi\formbuilder\Validator\Structure\Config
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 nox.kiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class FieldValidator extends ConfigValidator
{
    /**
     * @inheritDoc
     */
    protected array $structureDesign = [
        'field_id'          => 'text',
        'field_validator'   => 'text_validator',
        'field_name'        => 'text',
        'field_type'        => null,
        'field_value'       => null,
        'field_class'       => 'text',
        'field_required'    => 'boolean',
        'field_readonly'    => 'boolean',
        'field_title'       => 'text',
        'field_placeholder' => 'text'
    ];
}
