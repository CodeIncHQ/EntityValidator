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
abstract class EntityValidator implements EntityValidatorInterface
{
    /**
     * @var string[][]
     */
    private $errors = [];

    /**
     * Reports an error.
     *
     * @param string $errorMessage
     * @param null|string $fieldName
     */
    protected function reportError(string $fieldName, string $errorMessage = null):void
    {
        $this->errors[$fieldName][] = $errorMessage;
    }

    /**
     * @inheritdoc
     * @return string[][]
     */
    public function getErrors():array
    {
        return $this->errors;
    }

    /**
     * @inheritdoc
     * @param string $fieldName
     * @return array|null
     */
    public function getFieldErrors(string $fieldName):?array
    {
        return $this->errors[$fieldName] ?? null;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getFieldsWithError():array
    {
        return array_keys($this->errors);
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function hasError():bool
    {
        return !empty($this->errors);
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function countErrors():int
    {
        $count = 0;
        foreach ($this->errors as $fieldErrors) {
            $count += count($fieldErrors);
        }
        return $count;
    }

    /**
     * @inheritdoc
     * @uses EntityValidator::countErrors()
     * @return int
     */
    public function count():int
    {
        return $this->countErrors();
    }
}