<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder\Interfaces;

use noxkiwi\core\Datacontainer;
use noxkiwi\core\ErrorStack;
use noxkiwi\formbuilder\Field;
use noxkiwi\formbuilder\Fieldset;
use noxkiwi\formbuilder\Form;

/**
 * I am the interface for all forms.
 *
 * @package      noxkiwi\formbuilder\Interfaces
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2020 nox.kiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface FormInterface
{
    /**
     * I will overwrite the $callback for the given $identifier.
     * <br />Please add Callbacks by requiring an instance of Form named $Form.
     *
     * @example-     >addCallback(Form::CALLBACK_FORM_NOT_SENT, function(Form $Form) {echo 'Huh';});
     *
     * @param string   $identifier
     * @param callable $callback
     *
     * @return       Form
     */
    public function addCallback(string $identifier, callable $callback): Form;

    /**
     * Outputs the Form HTML code
     *
     * @return       string
     */
    public function output(): string;

    /**
     * Adds the given $Field to the Form instance
     *
     * @param Field $field
     *
     * @return       Form
     */
    public function addField(Field $field): Form;

    /**
     * Returns the errorstack instance of this Form
     *
     * @return       Errorstack
     */
    public function getErrorStack(): ErrorStack;

    /**
     * Returns the given $Field instance's value depending on it's datatype
     *
     * @param \noxkiwi\formbuilder\Field $field
     *
     * @return       mixed
     */
    public function fieldValue(Field $field): mixed;

    /**
     * Setter for the type of output to generate
     *
     * @param string $type
     *
     * @return       \noxkiwi\formbuilder\Form
     */
    public function setType(string $type): Form;

    /**
     * Returns all the fields in the Form instance
     *
     * @return       \noxkiwi\formbuilder\Field[]
     */
    public function getFields(): array;

    /**
     * Sets the identifier for the current Form
     *
     * @param string $identifier
     *
     * @return       \noxkiwi\formbuilder\Form
     */
    public function setId(string $identifier): Form;

    /**
     * Adds a $Fieldset to the instance
     *
     * @param \noxkiwi\formbuilder\Fieldset $fieldset
     *
     * @return       \noxkiwi\formbuilder\Form
     */
    public function addFieldset(Fieldset $fieldset): Form;

    /**
     * Returns the array of fieldsets here
     *
     * @return       \noxkiwi\formbuilder\Fieldset[]
     */
    public function getFieldsets(): array;

    /**
     * I am a standardized method for working off an existing Form instance.
     * <br />By default, the FORM_NOT_SENT callback will use the Form template to output it.
     * <br />By default, the FORM_NOT_VALID callback will output the occured errors using the standard outputs.
     *
     * @return       \noxkiwi\formbuilder\Form
     */
    public function work(): Form;

    /**
     * Returns true if the Form of the instance has been sent in a previous Request.
     * <br />This is determined by checking if the Request instance contains the "formSent.$FORMID" Field
     *
     * @return       bool
     */
    public function isSent(): bool;

    /**
     * Returns true if all fields of the Form have been filled correctly.
     * <br />Checks if required fields are filled
     * <br />Also uses the given field_types as validators to check the fields
     *
     * @param array $fieldIds
     *
     * @return       bool
     */
    public function isValid(array $fieldIds = null): bool;

    /**
     * Returns true if the given $Field has been submitted in the las Request
     * <br />Uses the Request object to find the fields's name
     *
     * @param Field $field
     *
     * @return       bool
     */
    public function fieldSent(Field $field): bool;

    /**
     * @return Datacontainer
     */
    public function getConfig(): Datacontainer;
}

