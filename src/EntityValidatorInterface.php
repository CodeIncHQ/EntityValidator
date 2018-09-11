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
 * Interface EntityValidatorInterface
 *
 * @package CodeInc\EntityValidator
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface EntityValidatorInterface extends \Countable
{
    /**
     * Returns all the errors in an assoc array sorted by fields.
     *
     * @return string[][]
     */
    public function getErrors():array;

    /**
     * Returns the errors for a given field.
     *
     * @param string $fieldName
     * @return array|null
     */
    public function getFieldErrors(string $fieldName):?array;

    /**
     * Returns the names of the fields with a least one reported error.
     *
     * @return array
     */
    public function getFieldsWithError():array;

    /**
     * Verifies if a least one error is reported.
     *
     * @return bool
     */
    public function hasError():bool;

    /**
     * Count the reported errors.
     *
     * @return int
     */
    public function countErrors():int;

    /**
     * @inheritdoc
     * @return int
     */
    public function count():int;
}