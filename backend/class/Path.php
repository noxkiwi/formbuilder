<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

/**
 * I am
 *
 * @package      noxkiwi\formbuilder
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 nox.kiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Path extends \noxkiwi\core\Path
{
    public const FRONTEND_DIR = '/var/www/_dev/vendor/noxkiwi/formbuilder/frontend/';
    public const FORM         = self::FRONTEND_DIR . 'form/';
    public const FIELDSET     = self::FRONTEND_DIR . 'fieldset/';
    public const FIELD        = self::FRONTEND_DIR . 'field/';
}

