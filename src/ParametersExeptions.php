<?php

namespace App;

use Exception;
/**
 * Исключения входных параметров API
 */
class ParametersExeptions
{
    /**
     * Не верный тип переменной
     *
     * @param string $parameterName Наименование входного API параметра
     * @param string $expectedType  Ожидаемый тип переменной
     * @param string $givenType     Тип переменной переданной в API
     *
     * @return Exception
     */
    public static function invalidType($parameterName, $expectedType, $givenType)
    {
        return new Exception(
            sprintf(
                'Wrong type specified for `%s`. Expected %s, %s given.',
                $parameterName,
                $expectedType,
                $givenType
            )
        );
    }

    /**
     * Недопустимое значение параметра
     *
     * @param string $parameterName Наименование
     *                              входного API
     *                              параметра
     * @param array  $allowedValues Массив
     *                              возможных
     *                              значений
     * @param string $givenValue    Тип переменной
     *                              переданной в API
     *
     * @return Exception
     */
    public static function outOfRange($parameterName, array $allowedValues, $givenValue)
    {
        return new Exception(
            sprintf(
                'Wrong value specified for `%s`. Allowed values {%s}, %s given.',
                $parameterName,
                implode(',', $allowedValues),
                $givenValue
            )
        );
    }

    /**
     * Параметр является обязательным
     *
     * @param  string $parameterName Наименование
     *                               входного API
     *                               параметра
     * @return Exception
     */
    public static function parameterIsRequired($parameterName)
    {
        return new Exception(
            sprintf(
                'Parameter `%s` is required.',
                $parameterName
            )
        );
    }

    /**
     * Переданный параметр меньше допустимого
     *
     * @param string    $parameterName  Наименование входного API параметра
     * @param integer   $minValue       Минимальное значение
     * @param integer   $givenValue     Переданное значение
     *
     * @return Exception
     */
    public static function lessThanMinimum($parameterName, $minValue, $givenValue)
    {
        return new Exception(
            sprintf(
                'Parameter `%s` Is less than the minimum value. Allowed min value: %s, %s given.',
                $parameterName,
                $minValue,
                $givenValue
            )
        );
    }

    /**
     * Переданный параметр короче допустимого
     *
     * @param string    $parameterName  Наименование входного API параметра
     * @param integer   $minLength      Минимальная длинна
     * @param integer   $givenLength    Переданная длинна
     *
     * @return Exception
     */
    public static function shorterThanMinimum($parameterName, $minLength, $givenLength)
    {
        return new Exception(
            sprintf(
                'Parameter `%s` Is shorter than the minimum value. Allowed min length: %s, %s given.',
                $parameterName,
                $minLength,
                $givenLength
            )
        );
    }

    /**
     * Переданный параметр больше допустимого
     *
     * @param string    $parameterName  Наименование входного API параметра
     * @param integer   $minValue       Минимальное значение
     * @param integer   $givenValue     Переданное значение
     *
     * @return Exception
     */
    public static function greaterThanMinimum($parameterName, $minValue, $givenValue)
    {
        return new Exception(
            sprintf(
                'Parameter `%s` Is greater than the maximum value. Allowed max value: %s, %s given.',
                $parameterName,
                $minValue,
                $givenValue
            )
        );
    }

    /**
     * Переданный параметр длиннее допустимого
     *
     * @param string    $parameterName  Наименование входного API параметра
     * @param integer   $maxLength      Максимальная длинна
     * @param integer   $givenLength    Переданная длинна
     *
     * @return Exception
     */
    public static function longerThanMinimum($parameterName, $maxLength, $givenLength)
    {
        return new Exception(
            sprintf(
                'Parameter `%s` Longer than the maximum value. Allowed max length: %s, %s given.',
                $parameterName,
                $maxLength,
                $givenLength
            )
        );
    }

    /**
     * Параметр не является валидным email'ом
     *
     * @param string    $parameterName  Наименование входного API параметра
     * @param string    $email          Значение переданного параметра
     *
     * @return Exception
     */
    public static function invalidEmail($parameterName, $email)
    {
        return new Exception(
            sprintf(
                'Parameter %s not a valid email. %s given',
                $parameterName,
                $email
            )
        );
    }

    /**
     * Параметр не является валидным телефоном
     *
     * @param string    $parameterName  Наименование входного API параметра
     * @param string    $phone          Значение переданного параметра
     *
     * @return Exception
     */
    public static function invalidPhone($parameterName, $phone)
    {
        return new Exception(
            sprintf(
                'Parameter %s not a valid phone. %s given',
                $parameterName,
                $phone
            )
        );
    }

    /**
     * Входной параметр не соответствует шаблону регулярного выражения
     *
     * @param string    $parameterName  Наименование входного API параметра
     * @param string    $pattern        Регулярное выражение
     * @param string    $givenValue     Переданное значение
     *
     * @return Exception
     */
    public static function invalidRegular($parameterName, $pattern, $givenValue)
    {
        return new Exception(
            sprintf(
                'Parameter %s not a valid. Expected value on pattern %s, %s given',
                $parameterName,
                $pattern,
                $givenValue
            )
        );
    }

    /**
     * Не верный тип значений массива
     *
     * @param string $parameterName Наименование входного API параметра
     * @param string $expectedType  Ожидаемый тип переменной
     * @param string $givenType     Тип переменной переданной в API
     *
     * @return Exception
     */
    public static function invalidArrayValueType($parameterName, $expectedType, $givenType)
    {
        return new Exception(
            sprintf(
                'Wrong array value type specified for `%s`. Expected %s, %s given.',
                $parameterName,
                $expectedType,
                $givenType
            )
        );
    }

    /**
     * Не верный тип ключа массива
     *
     * @param string $parameterName Наименование входного API параметра
     * @param string $expectedType  Ожидаемый тип переменной
     * @param string $givenType     Тип переменной переданной в API
     *
     * @return Exception
     */
    public static function invalidArrayKeyType($parameterName, $expectedType, $givenType)
    {
        return new Exception(
            sprintf(
                'Wrong array key type specified for `%s`. Expected %s, %s given.',
                $parameterName,
                $expectedType,
                $givenType
            )
        );
    }
}
