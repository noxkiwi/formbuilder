<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\formbuilder\Interfaces\FieldsetInterface;

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
final class Fieldset implements FieldsetInterface
{
    /** @var string Contains the display type of the instance */
    private string $type;
    /** @var array Contains all the data for the Form element */
    private array $data;

    /**
     * Creates a Fieldset. A Fieldset belongs into a Form and contains multiple instances of \noxkiwi\formbuilder\Field
     *
     * @param array $fieldset
     */
    public function __construct(array $fieldset)
    {
        $this->type = 'compact';
        if (isset($fieldset['fieldset_name'])) {
            $fieldset['fieldset_name'] = Translate::get('DATAFIELD.FIELDSET_' . $fieldset['fieldset_name']);
        } elseif (isset($fieldset['fieldset_name_override'])) {
            $fieldset['fieldset_name'] = $fieldset['fieldset_name_override'];
        } else {
            $fieldset['fieldset_name'] = '';
        }
        $this->data = $fieldset;
        if (! isset($this->get()['fields'])) {
            $this->data['fields'] = [];
        }
    }

    /**
     * Returns the instance's data array
     *
     * @return       array
     */
    protected function get(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function addField(Field $field): Fieldset
    {
        $this->data['fields'][$field->getName()] = $field;

        return $this;
    }

    /**
     * @return \noxkiwi\formbuilder\Field[]
     */
    public function getFields(): array
    {
        return $this->data['fields'];
    }

    /**
     * @inheritDoc
     */
    public function output(): string
    {
        return FrontendHelper::parseFile(Path::FIELDSET . $this->type . '.php', $this->get());
    }

    /**
     * @inheritDoc
     */
    public function setType(string $type): Fieldset
    {
        $this->type = $type;

        return $this;
    }
}

