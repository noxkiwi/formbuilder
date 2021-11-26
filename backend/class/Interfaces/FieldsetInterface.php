<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder\Interfaces;

use noxkiwi\formbuilder\Field;
use noxkiwi\formbuilder\Fieldset;

/**
 * I am the interface for the Fieldset obejcts.
 *
 * @package      noxkiwi\formbuilder\Interfaces
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 nox.kiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface FieldsetInterface
{
    /**
     * Adds a Field to the data array of this instance
     *
     * @param Field $field
     *
     * @return       Fieldset
     */
    public function addField(Field $field): Fieldset;

    /**
     * Will output the Fieldset's content
     *
     * @return       string
     */
    public function output(): string;

    /**
     * Setter for the type of output to generate
     *
     * @param string $type
     *
     * @return       Fieldset
     */
    public function setType(string $type): Fieldset;
}

