<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor\Validations;

/**
 * Class ValidationFactory
 *
 * @package Weburnit\Console\Commands\Processor\Validations
 */
class ValidationFactory
{
    const TYPE_NUMERIC    = 'numeric';
    const TYPE_ARRAY      = 'array';
    const TYPE_DATE       = 'date';
    const TYPE_INTEGER    = 'integer';
    const TYPE_EMAIL      = 'email';
    const TYPE_STRING     = 'string';
    const TYPE_TIMEZONE   = 'timezone';
    const TYPE_ALPHA      = 'alpha';
    const TYPE_ALPHA_DASH = 'alpha_dash';
    const TYPE_ALPHA_NUM  = 'alpha_num';
    const TYPE_BOOLEAN    = 'boolean';
    const TYPE_DISTINCT   = 'distinct';
    const TYPE_FILLED     = 'filled';
    const TYPE_FILE       = 'file';
    const TYPE_IMAGE      = 'image';
    const TYPE_IP         = 'ip';
    const TYPE_JSON       = 'json';
    const TYPE_PRESENT    = 'present';
    const TYPE_URL        = 'url';
    const TYPE_ACCEPTED   = 'accepted';
    const TYPE_ACTIVE_URL = 'active_url';
    const TYPE_CLASS      = 'class';


    const EXTENDED_TYPE_AFTER                = 'after';
    const EXTENDED_TYPE_BEFORE               = 'before';
    const EXTENDED_TYPE_BETWEEN              = 'between';
    const EXTENDED_TYPE_DATE_FORMAT          = 'date_format';
    const EXTENDED_TYPE_DIFFERENT            = 'different';
    const EXTENDED_TYPE_DIGITS_BETWEEN       = 'digits_between';
    const EXTENDED_TYPE_DIGITS               = 'digits';
    const EXTENDED_TYPE_EXISTS               = 'exists';
    const EXTENDED_TYPE_IN                   = 'in';
    const EXTENDED_TYPE_NOT_IN               = 'not_in';
    const EXTENDED_TYPE_IN_ARRAY             = 'in_array';
    const EXTENDED_TYPE_MAX                  = 'max';
    const EXTENDED_TYPE_MIN                  = 'min';
    const EXTENDED_TYPE_MIME_TYPES           = 'mimetypes';
    const EXTENDED_TYPE_MIMES                = 'mimes';
    const EXTENDED_TYPE_REGEX                = 'regex';
    const EXTENDED_TYPE_REQUIRED_IF          = 'required_if';
    const EXTENDED_TYPE_REQUIRED_UNLESS      = 'required_unless';
    const EXTENDED_TYPE_REQUIRED_WITH        = 'required_with';
    const EXTENDED_TYPE_REQUIRED_WITH_ALL    = 'required_with_all';
    const EXTENDED_TYPE_REQUIRED_WITHOUT     = 'required_without';
    const EXTENDED_TYPE_REQUIRED_WITHOUT_ALL = 'required_without_all';
    const EXTENDED_TYPE_REQUIRED_SAME        = 'same';
    const EXTENDED_TYPE_REQUIRED_SIZE        = 'size';
    const EXTENDED_TYPE_REQUIRED_UNIQUE      = 'unique';

    /**
     * @param string $validation
     *
     * @return BaseValidationProcessor|null
     */
    public function createValidation(string $validation)
    {
        $description = $this->hasExtendDescription($validation);
        if (static::TYPE_CLASS === $validation) {
            return new ClassValidationProcessor($description);
        }
        if ($description) {
            return new ExtendedValidationProcessor((string) $description);
        }

        return null;
    }

    /**
     * @return array
     */
    public static function getValidationOptions()
    {
        return [
            static::TYPE_ARRAY,
            static::TYPE_ACCEPTED,
            static::TYPE_ACTIVE_URL,
            static::TYPE_NUMERIC,
            static::TYPE_DATE,
            static::TYPE_INTEGER,
            static::TYPE_EMAIL,
            static::TYPE_STRING,
            static::TYPE_TIMEZONE,
            static::TYPE_ALPHA,
            static::TYPE_ALPHA_DASH,
            static::TYPE_ALPHA_NUM,
            static::TYPE_BOOLEAN,
            static::TYPE_DISTINCT,
            static::TYPE_FILE,
            static::TYPE_FILLED,
            static::TYPE_IMAGE,
            static::TYPE_IP,
            static::TYPE_JSON,
            static::TYPE_PRESENT,
            static::TYPE_URL,
            static::EXTENDED_TYPE_AFTER,
            static::EXTENDED_TYPE_BEFORE,
            static::EXTENDED_TYPE_BETWEEN,
            static::EXTENDED_TYPE_DATE_FORMAT,
            static::EXTENDED_TYPE_DIFFERENT,
            static::EXTENDED_TYPE_DIGITS_BETWEEN,
            static::EXTENDED_TYPE_DIGITS,
            static::EXTENDED_TYPE_EXISTS,
            static::EXTENDED_TYPE_IN,
            static::EXTENDED_TYPE_NOT_IN,
            static::EXTENDED_TYPE_IN_ARRAY,
            static::EXTENDED_TYPE_MAX,
            static::EXTENDED_TYPE_MIN,
            static::EXTENDED_TYPE_MIME_TYPES,
            static::EXTENDED_TYPE_MIMES,
            static::EXTENDED_TYPE_REGEX,
            static::EXTENDED_TYPE_REQUIRED_IF,
            static::EXTENDED_TYPE_REQUIRED_UNLESS,
            static::EXTENDED_TYPE_REQUIRED_WITH,
            static::EXTENDED_TYPE_REQUIRED_WITH_ALL,
            static::EXTENDED_TYPE_REQUIRED_WITHOUT,
            static::EXTENDED_TYPE_REQUIRED_WITHOUT_ALL,
            static::EXTENDED_TYPE_REQUIRED_SAME,
            static::EXTENDED_TYPE_REQUIRED_SIZE,
            static::EXTENDED_TYPE_REQUIRED_UNIQUE,
            static::TYPE_CLASS,
        ];
    }

    /**
     * @param string $validation
     *
     * @return null|string
     */
    public static function getDataType(string $validation)
    {
        if (static::TYPE_BOOLEAN === $validation) {
            return $validation;
        }
        $integerFields = [
            static::TYPE_INTEGER,
            static::EXTENDED_TYPE_REQUIRED_SIZE,
            static::TYPE_NUMERIC,
            static::TYPE_ALPHA_NUM,
            static::EXTENDED_TYPE_DIGITS,
            static::EXTENDED_TYPE_DIGITS_BETWEEN,
        ];
        if (in_array($validation, $integerFields)) {
            return static::TYPE_INTEGER;
        }
        $stringFields = [
            static::TYPE_ACCEPTED,
            static::TYPE_ACTIVE_URL,
            static::TYPE_URL,
            static::TYPE_STRING,
            static::EXTENDED_TYPE_MIMES,
            static::EXTENDED_TYPE_MIME_TYPES,
            static::EXTENDED_TYPE_REQUIRED_UNIQUE,
            static::EXTENDED_TYPE_REGEX,
            static::EXTENDED_TYPE_DATE_FORMAT,
            static::TYPE_DATE,
            static::TYPE_EMAIL,
            static::TYPE_TIMEZONE,
            static::TYPE_DISTINCT,
            static::TYPE_FILLED,
            static::TYPE_FILE,
            static::TYPE_IMAGE,
            static::TYPE_IP,
            static::TYPE_PRESENT,
        ];
        if (in_array($validation, $stringFields)) {
            return static::TYPE_STRING;
        }

        if (in_array($validation, [static::TYPE_CLASS, static::TYPE_JSON])) {
            return static::TYPE_JSON;
        }
        if (static::TYPE_ARRAY === $validation) {
            return $validation;
        }

        return null;
    }

    /**
     * @param string $type
     *
     * @return mixed|null
     * @codingStandardsIgnoreStart
     */
    private function hasExtendDescription(string $type)
    {
        $descriptions = [
            ValidationFactory::EXTENDED_TYPE_AFTER                => 'When?. You may specify another field or pass by strtotime',
            ValidationFactory::EXTENDED_TYPE_BEFORE               => 'When?. You may specify another field or pass by strtotime',
            ValidationFactory::EXTENDED_TYPE_BETWEEN              => 'Exp: 10,20',
            ValidationFactory::EXTENDED_TYPE_DATE_FORMAT          => 'Format string for your date',
            ValidationFactory::EXTENDED_TYPE_DIFFERENT            => 'Provide field which must be different to',
            ValidationFactory::EXTENDED_TYPE_DIGITS_BETWEEN       => 'Exp: 20,20',
            ValidationFactory::EXTENDED_TYPE_DIGITS               => 'Provide your value',
            ValidationFactory::EXTENDED_TYPE_EXISTS               => 'Provide your table and column. Exp: products,platformCode',
            ValidationFactory::EXTENDED_TYPE_IN                   => 'Combine values by comma. Exp: 1,2,20,30',
            ValidationFactory::EXTENDED_TYPE_NOT_IN               => 'Combine values by comma. Exp: 1,2,4,4',
            ValidationFactory::EXTENDED_TYPE_IN_ARRAY             => 'The field under validation must exist in anotherfield\'s values',
            ValidationFactory::EXTENDED_TYPE_MAX                  => 'Provide your max value',
            ValidationFactory::EXTENDED_TYPE_MIN                  => 'Provide your min value',
            ValidationFactory::EXTENDED_TYPE_MIME_TYPES           => 'Provide mime types. Exp: video/avi,text/plain',
            ValidationFactory::EXTENDED_TYPE_MIMES                => 'The file under validation must have a MIME type corresponding to one of the listed extensions.',
            ValidationFactory::EXTENDED_TYPE_REGEX                => 'Regex pattern',
            ValidationFactory::EXTENDED_TYPE_REQUIRED_IF          => 'The field under validation must be present and not empty if the anotherfield field is equal to any value.',
            ValidationFactory::EXTENDED_TYPE_REQUIRED_UNLESS      => 'The field under validation must be present and not empty unless the anotherfield field is equal to any value.',
            ValidationFactory::EXTENDED_TYPE_REQUIRED_WITH        => 'The field under validation must be present and not empty only if any of the other specified fields are present.',
            ValidationFactory::EXTENDED_TYPE_REQUIRED_WITH_ALL    => 'The field under validation must be present and not empty only if all of the other specified fields are present.',
            ValidationFactory::EXTENDED_TYPE_REQUIRED_WITHOUT     => 'The field under validation must be present and not empty only when any of the other specified fields are not present.',
            ValidationFactory::EXTENDED_TYPE_REQUIRED_WITHOUT_ALL => 'The field under validation must be present and not empty only when all of the other specified fields are not present.',
            ValidationFactory::EXTENDED_TYPE_REQUIRED_SAME        => 'The given field must match the field under validation.',
            ValidationFactory::EXTENDED_TYPE_REQUIRED_SIZE        => 'Size of your value such as string length or number items of array',
            ValidationFactory::EXTENDED_TYPE_REQUIRED_UNIQUE      => 'Reflect to you column. Exp: table,column,except,idColumn',
            ValidationFactory::TYPE_CLASS                         => 'Provide your class name',
        ];

        /**
         * @codingStandardsIgnoreEnd
         */
        return $descriptions[$type] ?? null;
    }
}
