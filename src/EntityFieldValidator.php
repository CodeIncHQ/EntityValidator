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
// Date:     11/09/2018
// Project:  EntityValidator
//
declare(strict_types=1);
namespace CodeInc\EntityValidator;

/**
 * Class EntityFieldValidator
 *
 * @package CodeInc\EntityValidator
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class EntityFieldValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var ValueValidator[]
     */
    private $valuesValidators = [];

    /**
     * EntityFieldValidator constructor.
     *
     * @param string $fieldName
     */
    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @param mixed $value
     * @return ValueValidator
     */
    public function value($value):ValueValidator
    {
        if (!isset($this->valuesValidators[$value])) {
            $this->valuesValidators[$value] = new ValueValidator($value);
        }
        return $this->valuesValidators[$value];
    }

    /**
     * @return string
     */
    public function getFieldName():string
    {
        return $this->fieldName;
    }

    /**
     * @param string $assertingWhat
     * @return string
     */
    protected function getAssertErrMsg(string $assertingWhat):string
    {
        return sprintf("Failed asserting that the field '%s' value %s",
            $this->fieldName, $assertingWhat);
    }

    /**
     * @inheritdoc
     * @return string[]
     */
    public function getErrors():array
    {
        $errors = [];
        foreach ($this->valuesValidators as $valueValidator) {
            if ($valueValidator->hasError()) {
                $errors = array_merge($errors, $valueValidator->getErrors());
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
        foreach ($this->valuesValidators as $valueValidator) {
            if ($valueValidator->hasError()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function count():int
    {
        $count = 0;
        foreach ($this->valuesValidators as $valueValidator) {
            $count += $valueValidator->count();
        }
        return $count;
    }
}