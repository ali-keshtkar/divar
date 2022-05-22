<?php

namespace Modules\Core\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as CODE;
use function base_path;
use function collect;

class Api
{
    #region Properties

    /**
     * Api response data.
     *
     * @var array
     */
    private array $_data = [];

    /**
     * Api response custom errors.
     *
     * @var array
     */
    private array $_errors = [];

    /**
     * Api Message.
     *
     * @var string
     */
    private string $_message;

    /**
     * Api response processed means [True].
     *
     * @var bool
     */
    private bool $_status = true;

    /**
     * Api response has some errors means [True].
     *
     * @var bool
     */
    private bool $_has_error = false;

    /**
     * Api response code.
     *
     * @var int
     */
    private int $_code = CODE::HTTP_OK;

    /**
     * Validation data.
     *
     * @var array
     */
    private array $_validation_data = [];

    /**
     * Validation rules.
     *
     * @var array
     */
    private array $_validation_rules = [];

    /**
     * Validation messages.
     *
     * @var array
     */
    private array $_validation_messages = [];

    /**
     * Validation attributes.
     *
     * @var array
     */
    private array $_validation_attributes = [];

    #endregion

    #region Getter and setters

    /**
     * Get completed api response.
     *
     * @return array
     */
    public function getResponse(): array
    {
        return [
            'status' => $this->getStatus(),
            'has_error' => $this->getHasError(),
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'errors' => $this->getErrors(),
            'data' => $this->getData(),
            'uri' => $this->getUri(),
        ];
    }

    /**
     * Add key value data.
     *
     * @param string|int $key
     * @param mixed $value
     * @return $this
     */
    public function addData(string|int $key, mixed $value): static
    {
        $this->_data[$key] = $value;
        return $this;
    }

    /**
     * Add key value error.
     *
     * @param string|int $key
     * @param mixed $value
     * @return $this
     */
    public function addError(string|int $key, mixed $value): static
    {
        $this->_errors[$key] = $value;
        return $this;
    }

    /**
     * Api response process status.
     *
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->_status;
    }

    /**
     * Get api has some errors.
     *
     * @return bool
     */
    public function getHasError(): bool
    {
        return $this->_has_error;
    }

    /**
     * Set api has some errors.
     *
     * @return $this
     */
    public function hasError(): static
    {
        $this->_has_error = true;
        return $this;
    }

    /**
     * Set Success status.
     *
     * @return $this
     */
    public function success(): static
    {
        $this->_status = true;
        return $this;
    }

    /**
     * Set Failed status.
     *
     * @return $this
     */
    public function failed(): static
    {
        $this->_status = false;
        return $this;
    }

    /**
     * Failed with has error.
     *
     * @param int $code
     * @return $this
     */
    public function failedWithError(int $code = 200): static
    {
        $this->_status=false;
        $this->_has_error=true;
        $this->setCode($code);
        return $this;
    }

    /**
     * Get current uri.
     *
     * @return string
     */
    public function getUri(): string
    {
        return Route::current()->uri();
    }

    /**
     * Get Api response http status code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->_code;
    }

    /**
     * Get Api response http status code.
     *
     * @param int $code
     * @return static
     */
    public function setCode(int $code): static
    {
        $this->_code = $code;
        return $this;
    }

    /**
     * Get Api response custom errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->_errors;
    }

    /**
     * Set Api response custom errors.
     *
     * @param array $errors
     * @return static
     */
    public function setErrors(array $errors): static
    {
        $this->_errors = $errors;
        return $this;
    }

    /**
     * Get Api response data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->_data;
    }

    /**
     * Set Api response data.
     *
     * @param array $data
     * @return static
     */
    public function setData(array $data): static
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * Get a api message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->_message;
    }

    /**
     * Set a api message.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): static
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * Get validation data.
     *
     * @return array
     */
    public function getValidationData(): array
    {
        return $this->_validation_data;
    }

    /**
     * Set validation data.
     *
     * @param array $validation_data
     * @return $this
     */
    public function setValidationData(array $validation_data)
    {
        $this->_validation_data = $validation_data;
        return $this;
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->_validation_rules;
    }

    /**
     * Set validation rules.
     *
     * @param array $validation_rules
     * @return $this
     */
    public function setValidationRules(array $validation_rules)
    {
        $this->_validation_rules = $validation_rules;
        return $this;
    }

    /**
     * Get validation messages.
     *
     * @return array
     */
    public function getValidationMessages(): array
    {
        return $this->_validation_messages;
    }

    /**
     * Set validation messages.
     *
     * @param array $validation_messages
     * @return $this
     */
    public function setValidationMessages(array $validation_messages)
    {
        $this->_validation_messages = $validation_messages;
        return $this;
    }

    /**
     * Get validation attributes.
     *
     * @return array
     */
    public function getValidationAttributes(): array
    {
        return $this->_validation_attributes;
    }

    /**
     * Set validation attributes.
     *
     * @param array $validation_attributes
     * @return $this;
     */
    public function setValidationAttributes(array $validation_attributes)
    {
        $this->_validation_attributes = $validation_attributes;
        return $this;
    }

    #endregion

    #region Initializer

    /**
     * Initialize  ApiResponse for message.
     *
     * @param string|null $message
     * @return static
     */
    public static function message(string $message = null): static
    {
        $self = new self();
        if ($message) $self->setMessage($message);
        return $self;
    }

    /**
     * Get new instance of Api
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     * @param bool $useDefaults
     * @param bool $doValidate
     * @return static
     */
    public static function check(array $data = [], array $rules = [], array $messages = [], array $attributes = [], bool $useDefaults = true, bool $doValidate = true): static
    {
        $self = new self();
        $self->setValidationData($data)
            ->setValidationRules($rules)
            ->setValidationMessages($messages)
            ->setValidationAttributes($attributes);
        if ($useDefaults) $self->useDefaults();
        if ($doValidate) $self->validate();
        return $self;
    }

    #endregion

    #region Logic

    /**
     * Initialize defaults validation attributes.
     *
     * @return $this
     */
    public function useDefaults(): static
    {
        $this->setValidationAttributes(Lang::get($this->_getNamespace() . '::attributes'));
        return $this;
    }

    /**
     * @return $this|void
     */
    public function validate()
    {
        $validator = Validator::make(
            $this->getValidationData(),
            $this->getValidationRules(),
            $this->getValidationMessages(),
            $this->getValidationAttributes()
        );
        if ($validator->fails()) {
            $this->setMessage(Lang::get('core::api.responses.UNPROCESSABLE_DATA'))
                ->setCode(CODE::HTTP_UNPROCESSABLE_ENTITY)
                ->setErrors($validator->errors()->toArray())
                ->hasError();
            Response::json([
                'status' => $this->getStatus(),
                'has_error' => $this->getHasError(),
                'code' => $this->getCode(),
                'message' => $this->getMessage(),
                'errors' => $this->getErrors(),
                'data' => $this->getData(),
                'uri' => $this->getUri(),
            ], $this->getCode())->send();
            die();
        }
        return $this;
    }

    /**
     * Get api response as a json.
     *
     * @return JsonResponse
     */
    public function done(): JsonResponse
    {
        return Response::json($this->getResponse(), $this->getCode());
    }

    /**
     * Set api response as a json.
     *
     * @param bool $withDie
     * @return void
     */
    public function send(bool $withDie=true)
    {
        Response::json($this->getResponse(), $this->getCode())->send();
        if ($withDie) die();
    }

    #endregion

    #region Built-In Helpers

    /**
     * Dynamically get current module namespace.
     *
     * @return string
     */
    private function _getNamespace()
    {
        return Str::lower(Str::before(Str::after(Arr::get($this->getDebugBackTrace(), 'file'), base_path('Modules\\')), '\\'));
    }

    /**
     * Get debug back trace specified function.
     *
     * @return mixed|null
     */
    private function getDebugBackTrace()
    {
        return collect(debug_backtrace())
            ->where('class', get_class())
            ->last();
    }

    #endregion
}
