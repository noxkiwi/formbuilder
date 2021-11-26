<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

use noxkiwi\core\Config;
use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Request;
use noxkiwi\formbuilder\Interfaces\FieldInterface;
use noxkiwi\validator\Validator;
use function is_callable;
use function is_string;

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
final class Field implements FieldInterface
{
    public const        FIELD_NONINPUT         = 'field_noninput';
    public const        FIELD_AJAX             = 'field_ajax';
    public const        FIELD_NAME             = 'field_name';
    public const        FIELD_TYPE             = 'field_type';
    public const        FIELD_DATATYPE         = 'field_datatype';
    public const        FIELD_DISPLAYTYPE      = 'field_datatype';
    public const        FIELD_VALIDATOROPTIONS = 'validator_options';
    public const        DISPLAYTYPE_CHECKBOX   = 'checkbox';
    public const        DATATYPE_BOOL          = 'bool';
    public const        FIELDTYPE_FILE         = 'file';
    public const        TOKEN_FIELDISREQUIRED  = 'field_required';
    public const        FIELD_TITLE            = 'title';
    public const        FIELD_DESCRIPTION      = 'description';
    public const        FIELD_DOMID            = 'domId';
    public const        FIELD_VALUE            = 'value';
    public const        FIELD_REQUIRED         = 'required';
    public const        FIELD_PLACEHOLDER      = 'placeholder';
    public const        FIELD_MULTIPLE         = 'multiple';
    public const        FIELD_READONLY         = 'readonly';
    public const        FIELD_DISPLAYFIELD     = 'displayfield';
    public const        FIELD_VALUEFIELD       = 'valuefield';
    public const        FIELD_VALIDATOR        = 'validator';
    public const        FIELD_ELEMENTS         = 'elements';
    public const        LABEL                  = 'label';
    /** @var string the type of output to generate */
    private string $type;
    /** @var \noxkiwi\core\Config Contains an instance of \noxkiwi\core\Config */
    private Config $config;

    /**
     * Creates instance of the Form class and sets it's data
     *
     * @param array $field
     */
    public function __construct(array $field)
    {
        $field        = $this->normalizeField($field);
        $this->type   = 'compact';
        $this->config = new Config($field);
    }

    /**
     * Sets defaults to the missing array keys
     *
     * @param array $field
     *
     * @return       array
     */
    protected function normalizeField(array $field): array
    {
        $field['field_type']        ??= 'input';
        $field['field_fieldtype']   ??= 'input';
        $field['field_id']          ??= $field['field_name'] ?? 'A';
        $field['field_class']       ??= 'input';
        $field['field_required']    ??= false;
        $field['field_readonly']    ??= false;
        $field['field_ajax']        ??= false;
        $field['field_noninput']    ??= false;
        $field['field_placeholder'] ??= '';
        $field['field_datatype']    ??= 'text';
        $field['field_validator']   ??= 'text';
        $field['field_description'] ??= '';
        $field[self::FIELD_VALUE]   ??= '';
        $field['field_title']       ??= '';
        $field['validator_options'] ??= [];
        if ($field['field_type'] === 'submit') {
            $field['field_noninput'] = true;
        }

        return $field;
    }

    /**
     * @return array
     */
    public function validate(): array
    {
        $validatorName = $this->getConfig()->get(self::FIELD_DATATYPE);
        if ($validatorName === null) {
            return [];
        }
        $displayType = $this->getConfig()->get(self::FIELD_DISPLAYTYPE);
        if ($displayType === self::DISPLAYTYPE_CHECKBOX) {
            $displayType = self::DATATYPE_BOOL;
        }

        return Validator::get($displayType)->validate(
            $this->getValue(),
            $this->getConfig()->get(self::FIELD_VALIDATOROPTIONS)
        );
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Returns the given $Field instance's value depending on it's datatype
     *
     * @return       mixed
     */
    public function getValue()
    {
        $fieldName = $this->getConfig()->get(self::FIELD_NAME);
        switch ($this->getConfig()->get('field_type')) {
            case 'checkbox':
                return ! empty(Request::getInstance()->get($fieldName));
            case 'file':
                return $_FILES[$fieldName];
            default:
                return Request::getInstance()->get($fieldName);
        }
    }

    /**
     * @inheritDoc
     */
    public function output(): string
    {
        $data = $this->config->get();
        // checking against is_string, Field content may be the string 'Max'
        if (! is_string($data[self::FIELD_VALUE]) && is_callable($data[self::FIELD_VALUE])) {
            $data[self::FIELD_VALUE] = $data[self::FIELD_VALUE]();
        }
        if (is_string($data[self::FIELD_VALUE])) {
            $data[self::FIELD_VALUE] = $this->makeFormReady($data[self::FIELD_VALUE]);
        }

        return FrontendHelper::parseFile(Path::FIELD . 'compact/' . $this->config->get('field_type') . '.php', $data);
    }

    /**
     * I will make the given string outputtable into a <input element.
     *
     * @param string $string
     *
     * @return string
     */
    private function makeFormReady(string $string): string
    {
        return (string)str_replace('"', '&quot;', $string);
    }

    /**
     * @inheritDoc
     */
    public function setType(string $type): Field
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isRequired(): bool
    {
        return $this->config->get(self::TOKEN_FIELDISREQUIRED);
    }

    public function getName(): string
    {
        return (string)$this->config->get(self::FIELD_NAME);
    }
}

