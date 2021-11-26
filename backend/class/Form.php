<?php declare(strict_types = 1);
namespace noxkiwi\formbuilder;

use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Datacontainer;
use noxkiwi\core\ErrorStack;
use noxkiwi\core\Exception;
use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Helper\LinkHelper;
use noxkiwi\core\Request;
use noxkiwi\core\Response;
use noxkiwi\core\Traits\ErrorstackTrait;
use noxkiwi\formbuilder\Interfaces\FormInterface;
use noxkiwi\formbuilder\Validator\Structure\Config\FormValidator;
use function call_user_func;
use function is_array;

/**
 * I am the form class.
 *
 * @package      noxkiwi\formbuilder
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 nox.kiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class Form implements FormInterface
{
    use ErrorstackTrait;

    public const FORM_ACTION             = 'form_action';
    public const FORM_METHOD             = 'form_method';
    public const CALLBACK_FORM_NOT_SENT  = 'FORM_NOT_SENT';
    public const CALLBACK_FORM_NOT_VALID = 'FORM_NOT_VALID';
    public const CALLBACK_FORM_VALID     = 'FORM_VALID';
    /** @var string I am the default type that will be instanced whenever creating a form. */
    public static string $defaultType = 'default';
    /** @var \noxkiwi\core\Datacontainer Contains the configuration for the Form */
    protected Datacontainer $config;
    /** @var \noxkiwi\formbuilder\Field[] Contains the Form fields for the Form */
    protected array $fields = [];
    /** @var \noxkiwi\formbuilder\Fieldset[] Contains all the fieldsets that will be displayed in the CRUD generator */
    protected array $fieldsets = [];
    /** @var \noxkiwi\core\Datacontainer Contains an array of data (fieldnames as keys, their sent value as value) */
    private Datacontainer $data;
    /** @var string Defines what Form and Field objects shall be used when generating output */
    private string $type;
    /** @var \noxkiwi\core\Errorstack Contains the errorStack for this Form */
    private ErrorStack $errorStack;
    /** @var callable[] Contains a list of callbacks */
    private array $callbacks = [];
    /** @var \noxkiwi\core\Request */
    private Request $request;

    /**
     * Stores the configuration and fields in the instance
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (empty($data[static::FORM_ACTION])) {
            $data[static::FORM_ACTION] = LinkHelper::makeUrl();
        }
        try {
            $errors = FormValidator::getInstance()->validate($data);
        } catch (Exception $exception) {
            $errors = [];
        }
        $this->addError('INVALID_CONFIG', $errors);
        $this->request    = Request::getInstance();
        $this->type       = static::$defaultType;
        $this->config     = new Datacontainer($data);
        $this->errorStack = ErrorStack::getErrorStack('VALIDATION');
        $this->addCallback(static::CALLBACK_FORM_NOT_SENT, static function (Form $form) {
            Response::getInstance()->set('form', $form->output());
        })->addCallback(static::CALLBACK_FORM_NOT_VALID, static function (Form $form) {
            Response::getInstance()->set(
                'errors',
                $form->getErrorStack()->getAll()
            );
            Response::getInstance()->set(Mvc::CONTEXT, 'form');
            Response::getInstance()->set(Mvc::VIEW, 'error');
        });
    }

    /**
     * @inheritDoc
     */
    public function addCallback(string $identifier, callable $callback): Form
    {
        $this->callbacks[$identifier] = $callback;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function output(): string
    {
        $this->addField(new Field([Field::FIELD_NAME => $this->getFormSentName(), 'field_type' => 'hidden', 'field_value' => $this->getFormSentValue()]));

        return FrontendHelper::parseFile(Path::FORM . $this->type . '.php', $this);
    }

    /**
     * @inheritDoc
     */
    public function addField(Field $field): Form
    {
        $this->fields[$field->getConfig()->get(Field::FIELD_NAME)] = $field;

        return $this;
    }

    /**
     * I will return the name of the hidden ~sent field (that determines whether the form was submitted.
     * I make that every session has it's own sent-identifier and protect the service from copied requests
     *
     * @return       string
     */
    private function getFormSentName(): string
    {
        return hash('sha512', $this->config->get('form_id') . $this->getSessioncode());
    }

    /**
     * I will return the sessioncode for enhancement for all form fields.
     *
     * @return       string
     */
    private function getSessioncode(): string
    {
        return hash('sha512', session_id()) ?? '';
    }

    /**
     * I will return the name of the hidden ~sent field (that determines whether the form was submitted.
     * I make that every session has it's own sent-identifier and protect the service from copied requests
     *
     * @return       string
     */
    private function getFormSentValue(): string
    {
        return hash('sha512', $this->getSessioncode());
    }

    /**
     * @inheritDoc
     */
    public function getErrorStack(): ErrorStack
    {
        return $this->errorStack;
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $data = new Datacontainer();
        foreach ($this->fields as $field) {
            $data->set(
                $field->getConfig()->get(Field::FIELD_NAME),
                $field->getValue()
            );
        }
        $flagValue = null;
        $flagName  = null;
        foreach ($this->fieldsets as $fieldset) {
            foreach ($fieldset->getFields() as $field) {
                if (stripos($field->getName(), 'flag') !== false) {
                    if ($flagValue === null) {
                        $flagValue = 0;
                    }
                    $flagStuph = explode('[', $field->getName());
                    $flagName  = $flagStuph[0];
                    $flagField = $flagStuph[1];
                    if ($field->getValue()) {
                        $flagValue += (int)explode(']', $flagField)[0];
                    }
                }
                $data->set(
                    $field->getConfig()->get(Field::FIELD_NAME),
                    $field->getValue()
                );
            }
        }
        if ($flagName !== null) {
            $data->set($flagName, $flagValue);
        }

        return (array)$data->get();
    }

    /**
     * @inheritDoc
     */
    public function fieldValue(Field $field): mixed
    {
        switch ($field->getConfig()->get('field_type')) {
            case 'checkbox':
                return $this->request->exists(
                    $field->getConfig()->get(Field::FIELD_NAME)
                );
            case 'file':
                return $_FILES[$field->getConfig()->get(Field::FIELD_NAME)];
            default:
                return $this->request->get(
                    $field->getConfig()->get(Field::FIELD_NAME)
                );
        }
    }

    /**
     * @inheritDoc
     */
    public function setType(string $type): Form
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @inheritDoc
     */
    public function setId(string $identifier): Form
    {
        $this->config->set('form_id', 'Core_form_' . $identifier);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addFieldset(Fieldset $fieldset): Form
    {
        $this->fieldsets[] = $fieldset;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFieldsets(): array
    {
        return $this->fieldsets;
    }

    /**
     * @inheritDoc
     */
    public function work(): Form
    {
        if (! $this->isSent()) {
            $this->fireCallback(static::CALLBACK_FORM_NOT_SENT);

            return $this;
        }
        if (! $this->isValid()) {
            $this->fireCallback(static::CALLBACK_FORM_NOT_VALID);

            return $this;
        }
        $this->fireCallback(static::CALLBACK_FORM_VALID);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isSent(): bool
    {
        return true;
    }

    /**
     * I will try accessing the callback identified by the given $identifier.
     * <br />I will pass the current instance of Form to the callback method named $Form
     * <br />If the desired callback does not exist, I will do nothing.
     *
     * @param string $identifier
     *
     * @return       Form
     */
    private function fireCallback(string $identifier): Form
    {
        if (isset($this->callbacks[$identifier])) {
            call_user_func($this->callbacks[$identifier], $this);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isValid(array $fieldIds = null): bool
    {
        foreach ($this->fields as $field) {
            if ($field->getConfig()->get(Field::FIELD_NONINPUT, false) === true) {
                continue;
            }
            if ($field->getConfig()->get(Field::FIELD_AJAX, false) === true) {
                continue;
            }
            $errors = $field->validate();
            if (! empty($errors)) {
                /** @var \noxkiwi\core\Error $error */
                $error = $errors[0];
                $this->errorStack->addError(
                    $error->getMessage(),
                    $error->getDetail()
                );
                continue;
            }
        }
        if (! empty($fieldIds)) {
            // some Field ids do not exist - $fieldIds has to be of size zero here.
            return false;
        }

        return $this->errorStack->isSuccess();
    }

    /**
     * @inheritDoc
     */
    public function fieldSent(Field $field): bool
    {
        if ($field->getConfig()->get(Field::FIELD_TYPE) === Field::FIELDTYPE_FILE) {
            return isset(
                $_FILES[$field->getConfig()->get(Field::FIELD_NAME)]
            );
        }
        $request = $this->request->get(
            $field->getConfig()->get(Field::FIELD_NAME)
        );
        if (is_array($request)) {
            return ! empty($request);
        }

        return ! empty($request);
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): Datacontainer
    {
        return $this->config;
    }
}

