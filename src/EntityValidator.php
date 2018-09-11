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
    private $fieldsValidators = [];

    /**
     * Returns a field validator.
     *
     * @param string $fieldName
     * @return EntityFieldValidator
     */
    public function field(string $fieldName):EntityFieldValidator
    {
        if (!isset($this->fieldsValidators[$fieldName])) {
            $this->fieldsValidators[$fieldName] = new EntityFieldValidator($fieldName);
        }
        return $this->fieldsValidators[$fieldName];
    }

    /**
     * Returns all the fields validators.
     *
     * @return EntityFieldValidator[]
     */
    public function getFields():array
    {
        return $this->fieldsValidators;
    }

    /**
     * Returns all the fields validators with at least an error.
     *
     * @return array
     */
    public function getFieldsWithError():array
    {
        $validators = [];
        foreach ($this->fieldsValidators as $fieldValidator) {
            if ($fieldValidator->hasError()) {
                $validators[$fieldValidator->getFieldName()] = $fieldValidator;
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
        foreach ($this->fieldsValidators as $fieldValidator) {
            if ($fieldValidator->hasError()) {
                $errors = array_merge($errors, $fieldValidator->getErrors());
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
        foreach ($this->fieldsValidators as $fieldValidator) {
            if ($fieldValidator->hasError()) {
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
        foreach ($this->fieldsValidators as $fieldsValidator) {
            $count += $fieldsValidator->count();
        }
        return $count;
    }
}