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
class FormValidator extends ConfigValidator
{
    /**
     * @inheritDoc
     */
    protected array $structureDesign = [
        'form_id'     => 'text',
        'form_action' => 'text',
        'form_method' => 'text'
    ];
}
