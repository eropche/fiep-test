<?php
declare(strict_types = 1);

namespace App;

use App\ParametersExeptions;

/**
 * Валидатор входных запросов
 */
class Validator
{
    private $validators = [];

    /**
     * Конструктор.
     *
     * @param $validators
     */
    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * Валидация входного запроса
     *
     * @param array $request    Запрос в виде массива который необходимо провалидировать
     *
     * @throws ParametersExeptions
     */
    public function validate(array $request)
    {
        $this->request = $request;

        foreach ($this->validators as $parameterId => $validators) {
            if (in_array('required', $validators, true)) {
                $this->validateRequire($parameterId);
            } elseif (!isset($this->request[$parameterId])) {
                continue;
            }

            if (isset($validators['type'])) {
                $this->validateType($parameterId, $validators['type']);
            }

            if (isset($validators['arrayValueTypes'])) {
                $this->validateArrayValueTypes($parameterId, $validators['arrayValueTypes']);
            }

            if (isset($validators['arrayKeyTypes'])) {
                $this->validateArrayKeyTypes($parameterId, $validators['arrayKeyTypes']);
            }

            if (isset($validators['range'])) {
                $this->validateRange($parameterId, $validators['range']);
            }

            if (isset($validators['min'])) {
                $this->validateMin($parameterId, $validators['min']);
            }

            if (isset($validators['max'])) {
                $this->validateMax($parameterId, $validators['max']);
            }

            if (isset($validators['match'])) {
                $this->validateMatch($parameterId, $validators['match']);
            }
        }
    }

    /**
     * Валидация допустимых типов параметра
     *
     * @param string $parameterId   Идентификатор параметра
     * @param string $types         Возможные типы
     *
     * @throws ParametersExeptions
     */
    protected function validateType($parameterId, $types)
    {
        $expecterdTypes = (array) $types;
        if (in_array('phone', $expecterdTypes)) {
            $this->validatePhone($parameterId);
            return;
        }
        $paramType = gettype($this->request[$parameterId]);
        if (!in_array($paramType, $expecterdTypes, true)) {
            $expecterdTypes = implode('|', $expecterdTypes);
            throw ParametersExeptions::invalidType($parameterId, $expecterdTypes, $paramType);
        }
    }

    /**
     * Валидация допустимых значений
     *
     * @param string    $parameterId    Идентификатор параметра
     * @param array     $range          Массив допустимых значений
     *
     * @throws ParametersExeptions
     */
    protected function validateRange($parameterId, $range)
    {
        $parameter = strtolower($this->request[$parameterId]);
        if (!isset($range[$parameter]) && !in_array($parameter, $range)) {
            throw ParametersExeptions::outOfRange($parameterId, $range, $this->request[$parameterId]);
        }
    }

    /**
     * Валидация минимально допустимого значения
     *
     * @param string    $parameterId    Идентификатор параметра
     * @param int       $minValue       Минимально допустимое значение
     *
     * @throws ParametersExeptions
     */
    protected function validateMin($parameterId, $minValue)
    {
        if (is_int($this->request[$parameterId]) && $this->request[$parameterId] < $minValue) {
            throw ParametersExeptions::lessThanMinimum($parameterId, $minValue, $this->request[$parameterId]);
        }

        if (is_string($this->request[$parameterId]) && strlen($this->request[$parameterId]) < $minValue) {
            throw ParametersExeptions::shorterThanMinimum($parameterId, $minValue, strlen($this->request[$parameterId]));
        }
    }

    /**
     * Валидация максимально допустимого значения
     *
     * @param string    $parameterId    Идентификатор параметра
     * @param int       $maxValue       Максимально допустимое значение
     *
     * @throws ParametersExeptions
     */
    protected function validateMax($parameterId, $maxValue)
    {
        if (is_int($this->request[$parameterId]) && $this->request[$parameterId] > $maxValue) {
            throw ParametersExeptions::greaterThanMinimum($parameterId, $maxValue, $this->request[$parameterId]);
        }

        if (is_string($this->request[$parameterId]) && strlen($this->request[$parameterId]) > $maxValue) {
            throw ParametersExeptions::longerThanMinimum($parameterId, $maxValue, strlen($this->request[$parameterId]));
        }
    }

    /**
     * Валидация по регулярному выражению
     * применяется только для 'string', 'integer', 'float', 'double'
     *
     * @param string    $parameterId    Идентификатор параметра
     * @param string    $pattern        Регулярное выражение для проверки
     *
     * @throws ParametersExeptions
     */
    protected function validateMatch($parameterId, $pattern)
    {
        $expecterdTypes = ['string', 'integer', 'float', 'double'];
        $paramType      = gettype($this->request[$parameterId]);
        if (in_array($paramType, $expecterdTypes, true) && !preg_match($pattern, (string) $this->request[$parameterId])) {
            throw ParametersExeptions::invalidRegular($parameterId, $pattern, $this->request[$parameterId]);
        }
    }

    /**
     * Валидация номера телефона
     *
     * @param $parameterId
     *
     * @throws \Exception
     */
    protected function validatePhone($parameterId)
    {
        $expecterdTypes = ['string'];
        $paramType      = gettype($this->request[$parameterId]);
        $pattern        = '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/';
        if (in_array($paramType, $expecterdTypes, true) && !preg_match($pattern, (string) $this->request[$parameterId])) {
            throw ParametersExeptions::invalidPhone($parameterId, $this->request[$parameterId]);
        }
    }

    /**
     * Валидация обязательных параметров
     *
     * @param string $parameterId
     *
     * @throws ParametersExeptions
     */
    protected function validateRequire($parameterId)
    {
        if (!isset($this->request[$parameterId]) || null === $this->request[$parameterId]) {
            throw ParametersExeptions::parameterIsRequired($parameterId);
        }

        if (is_array($this->request[$parameterId]) && empty($this->request[$parameterId])) {
            throw ParametersExeptions::parameterIsRequired($parameterId);
        }
    }

    /**
     * Валидация допустимых типов значений массива
     *
     * @param string $parameterId   Идентификатор параметра
     * @param string $types         Возможные типы
     *
     * @throws ParametersExeptions
     */
    protected function validateArrayValueTypes($parameterId, $types)
    {
        if ('array' !== gettype($this->request[$parameterId])) {
            return;
        }

        $expecterdTypes = (array) $types;

        foreach ($this->request[$parameterId] as $key => $value) {
            $paramType = gettype($value);
            if (!in_array($paramType, $expecterdTypes, true)) {
                $expecterdTypes = implode('|', $expecterdTypes);
                throw ParametersExeptions::invalidArrayValueType($parameterId, $expecterdTypes, $paramType);
            }
        }
    }

    /**
     * Валидация допустимых типов значений массива
     *
     * @param string $parameterId   Идентификатор параметра
     * @param string $types         Возможные типы
     *
     * @throws ParametersExeptions
     */
    protected function validateArrayKeyTypes($parameterId, $types)
    {
        if ('array' !== gettype($this->request[$parameterId])) {
            return;
        }

        $expecterdTypes = (array) $types;

        foreach ($this->request[$parameterId] as $key => $value) {
            $paramType = gettype($key);
            if (!in_array($paramType, $expecterdTypes, true)) {
                $expecterdTypes = implode('|', $expecterdTypes);
                throw ParametersExeptions::invalidArrayValueType($parameterId, $expecterdTypes, $paramType);
            }
        }
    }
}
