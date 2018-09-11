<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material is strictly forbidden unless prior    |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     10/09/2018
// Project:  EntityValidator
//
declare(strict_types=1);
namespace CodeInc\EntityValidator;

/**
 * Class EntityValidator
 *
 * @package CodeInc\EntityValidator
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class EntityValidator implements ValidatorInterface
{
    /**
     * @var EntityFieldValidator[]
     */
    private $validators = [];

    /**
     * Returns a field validator.
     *
     * @param string $fieldName
     * @param mixed $value
     * @return EntityFieldValidator
     */
    public function field(string $fieldName, $value):EntityFieldValidator
    {
        $validator = new EntityFieldValidator($fieldName, $value);
        $this->validators[] = $validator;
        return $validator;
    }

    /**
     * Returns a value validator.
     *
     * @param string $value
     * @return ValueValidator
     */
    public function value(string $value):ValueValidator
    {
        $validator = new ValueValidator($value);
        $this->validators[] = $validator;
        return $validator;
    }

    /**
     * Returns all the validators with at least an error.
     *
     * @param bool $onlyWithErrors
     * @return ValidatorInterface[]
     */
    public function getValidators(bool $onlyWithErrors = false):array
    {
        if ($onlyWithErrors) {
            $validators = [];
            foreach ($this->validators as $validator) {
                if ($validator->hasError()) {
                    $validators[] = $validator;
                }
            }
            return $validators;
        }

        return $this->validators;
    }

    /**
     * Returns all the fields validators with at least an error.
     *
     * @param bool $onlyWithErrors
     * @return array
     */
    public function getFieldsValidators(bool $onlyWithErrors = false):array
    {
        $validators = [];
        foreach ($this->validators as $validator) {
            if ($validator instanceof EntityFieldValidator && (!$onlyWithErrors || $validator->hasError())) {
                $validators[] = $validator;
            }
        }
        return $validators;
    }

    /**
     * @inheritdoc
     * @return string[]
     */
    public function getErrors():array
    {
        $errors = [];
        foreach ($this->validators as $validator) {
            if ($validator->hasError()) {
                $errors = array_merge($errors, $validator->getErrors());
            }
        }
        return $errors;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function hasError():bool
    {
        foreach ($this->validators as $validator) {
            if ($validator->hasError()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     * @uses EntityValidator::countErrors()
     * @return int
     */
    public function count():int
    {
        $count = 0;
        foreach ($this->validators as $validator) {
            $count += $validator->count();
        }
        return $count;
    }
}