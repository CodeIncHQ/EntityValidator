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
class EntityFieldValidator extends Validator
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * EntityFieldValidator constructor.
     *
     * @param string $fieldName
     * @param mixed $value
     */
    public function __construct(string $fieldName, $value)
    {
        $this->fieldName = $fieldName;
        parent::__construct($value);
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
}