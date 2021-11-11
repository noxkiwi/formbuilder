<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder\Interfaces;

use noxkiwi\core\Config;
use noxkiwi\formbuilder\Field;

/**
 * I am the interface Field objects
 *
 * @package      noxkiwi\formbuilder\Interfaces
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2020 nox.kiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface FieldInterface
{
    /**
     * Generates the output for the Form and returns it.
     *
     * @return       string
     */
    public function output(): string;

    /**
     * Setter for the type of output to generate
     *
     * @param string $type
     *
     * @return       Field
     */
    public function setType(string $type): Field;

    /**
     * Returns the config instance of this Field
     *
     * @return       Config
     */
    public function getConfig(): Config;

    /**
     * Returns true if the Field is required
     *
     * @return       bool
     */
    public function isRequired(): bool;
}
