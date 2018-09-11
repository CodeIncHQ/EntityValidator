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
class EntityValidator implements EntityValidatorInterface
{
    /**
     * @var string[][]
     */
    private $errors = [];

    /**
     * Reports an error.
     *
     * @param string $errorMessage
     * @param string $fieldName
     */
    public function reportError(string $fieldName, string $errorMessage):void
    {
        $this->errors[$fieldName][] = $errorMessage;
    }

    /**
     * @uses filter_var()
     * @param mixed $value
     * @param int $filter
     * @param string $fieldName
     * @param string $errorMessage
     * @param mixed|null $filterOptions
     * @return bool
     */
    public function assertFilter($value, int $filter, string $fieldName, string $errorMessage, $filterOptions = null):bool
    {
        if (!filter_var((string)$value, $filter, $filterOptions)) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertStringIsEmail($value, string $fieldName, string $errorMessage):bool
    {
        return $this->assertFilter($value, FILTER_VALIDATE_EMAIL, $fieldName, $errorMessage);
    }

    /**
     * @param mixed $value
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertStringIsUrl($value, string $fieldName, string $errorMessage):bool
    {
        return $this->assertFilter($value, FILTER_VALIDATE_URL, $fieldName, $errorMessage);
    }

    /**
     * @param mixed $value
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertStringIsIpV4($value, string $fieldName, string $errorMessage):bool
    {
        return $this->assertFilter($value, FILTER_VALIDATE_IP, $fieldName, $errorMessage);
    }

    /**
     * @param mixed $value
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertNotEmpty($value, string $fieldName, string $errorMessage):bool
    {
        if (empty($value)) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertNotNull($value, string $fieldName, string $errorMessage):bool
    {
        if ($value === null) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertIsArray($value, string $fieldName, string $errorMessage):bool
    {
        if (!is_array($value)) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @uses preg_match()
     * @param mixed $value
     * @param string $regExp
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertRegExp($value, string $regExp, string $fieldName, string $errorMessage):bool
    {
        if (!preg_match($regExp, $value)) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param int $minLength
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertMinLength($value, int $minLength, string $fieldName, string $errorMessage):bool
    {
        if (strlen($value) < $minLength) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param int $greaterThan
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertGreaterThan($value, int $greaterThan, string $fieldName, string $errorMessage):bool
    {
        if ($value <= $greaterThan) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param int $greaterThan
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertGreaterThanOrEqual($value, int $greaterThan, string $fieldName, string $errorMessage):bool
    {
        if ($value < $greaterThan) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param int $lowerThan
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertLessThan($value, int $lowerThan, string $fieldName, string $errorMessage):bool
    {
        if ($value >= $lowerThan) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param int $lowerThan
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertLessThanOrEqual($value, int $lowerThan, string $fieldName, string $errorMessage):bool
    {
        if ($value > $lowerThan) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param mixed $equal
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertEqual($value, $equal, string $fieldName, string $errorMessage):bool
    {
        if ($value == $equal) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param mixed $strictEqual
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertStrictEqual($value, $strictEqual, string $fieldName, string $errorMessage):bool
    {
        if ($value === $strictEqual) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param int $maxLength
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertMaxLength($value, int $maxLength, string $fieldName, string $errorMessage):bool
    {
        if (strlen($value) > $maxLength) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param \DateTime $dateTime
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertInPast(\DateTime $dateTime, string $fieldName, string $errorMessage):bool
    {
        if ($dateTime < new \DateTime()) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param \DateTime $dateTime
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertInFuture(\DateTime $dateTime, string $fieldName, string $errorMessage):bool
    {
        if ($dateTime > new \DateTime()) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param bool|mixed $condition
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assert($condition, string $fieldName, string $errorMessage):bool
    {
        if (!$condition) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param int $count
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertCount($value, int $count, string $fieldName, string $errorMessage):bool
    {
        if (count($value) != $count) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param array $array
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertInArray($value, array $array, string $fieldName, string $errorMessage):bool
    {
        if (!in_array($value, $array)) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param string $type
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertInternalType($value, string $type, string $fieldName, string $errorMessage):bool
    {
        if (gettype($value) != $type) {
            $this->reportError($fieldName, $errorMessage);
            return false;
        }
        return true;
    }

    /**
     * @param mixed $value
     * @param string $startsWith
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertStringStartsWith($value, string $startsWith, string $fieldName, string $errorMessage):bool
    {
        return $this->assertRegExp(
            $value,
            '/^'.preg_quote($startsWith, '/').'/ui',
            $fieldName,
            $errorMessage
        );
    }

    /**
     * @param mixed $value
     * @param string $endsWith
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertStringEndsWith($value, string $endsWith, string $fieldName, string $errorMessage):bool
    {
        return $this->assertRegExp(
            $value,
            '/'.preg_quote($endsWith, '$/').'/ui',
            $fieldName,
            $errorMessage
        );
    }

    /**
     * @param mixed $value
     * @param string $contains
     * @param string $fieldName
     * @param string $errorMessage
     * @return bool
     */
    public function assertStringContains($value, string $contains, string $fieldName, string $errorMessage):bool
    {
        return $this->assertRegExp(
            $value,
            '/'.preg_quote($contains, '$/').'/ui',
            $fieldName,
            $errorMessage
        );
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