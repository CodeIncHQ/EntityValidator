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
 * Class ValueValidator
 *
 * @package CodeInc\EntityValidator
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ValueValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param mixed|bool $condition
     * @param string $errorMessage
     * @return bool
     */
    public function assert($condition, string $errorMessage):bool
    {
        if (!$condition) {
            $this->reportError($errorMessage);
            return false;
        }
        return true;
    }


    /**
     * @return array
     */
    public function getErrors():array
    {
        return $this->errors;
    }

    /**
     * @param string $errorMessage
     */
    public function reportError(string $errorMessage):void
    {
        $this->errors[] = $errorMessage;
    }

    /**
     * @return bool
     */
    public function hasError():bool
    {
        return !empty($this->errors);
    }

    /**
     * @return int
     */
    public function count():int
    {
        return count($this->errors);
    }

    /**
     * @param string $assertingWhat
     * @return string
     */
    protected function getAssertErrMsg(string $assertingWhat):string
    {
        return sprintf("Failed asserting that %s", $assertingWhat);
    }

    /**
     * @uses filter_var()
     * @param mixed $value
     * @param int $filter
     * @param string $errorMessage
     * @param null $filterOptions
     * @return bool
     */
    public function assertFilter($value, int $filter, string $errorMessage, $filterOptions = null):bool
    {
        return $this->assert(
            filter_var((string)$value, $filter, $filterOptions),
            $errorMessage
        );
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertStringIsEmail($value, ?string $errorMessage = null):bool
    {
        return $this->assertFilter(
            $value,
            FILTER_VALIDATE_EMAIL,
            $errorMessage ?? $this->getAssertErrMsg(sprintf("'%s' is an email", $value))
        );
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertStringIsUrl($value, ?string $errorMessage = null):bool
    {
        return $this->assertFilter(
            $value,
            FILTER_VALIDATE_URL,
            $errorMessage ?? $this->getAssertErrMsg(sprintf("'%s' is a URL", $value))
        );
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertStringIsIp($value, ?string $errorMessage = null):bool
    {
        return $this->assertFilter(
            $value,
            FILTER_VALIDATE_IP,
            $errorMessage ?? $this->getAssertErrMsg(sprintf("'%s' is an IP", $value))
        );
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertNotEmpty($value, ?string $errorMessage = null):bool
    {
        return $this->assert(
            !empty($value),
            $errorMessage ?? $this->getAssertErrMsg("is not empty")
        );
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertEmpty($value, ?string $errorMessage = null):bool
    {
        return $this->assert(
            empty($value),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is empty (actual '%s')", $value)
            )
        );
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertNotNull($value, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value !== null,
            $errorMessage ?? $this->getAssertErrMsg("is not null")
        );
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertNull($value, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value === null,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is null (actual '%s')", $value)
            )
        );
    }

    /**
     * @param $value
     * @param string $errorMessage
     * @return bool
     */
    public function assertIsArray($value, string $errorMessage):bool
    {
        return $this->assert(
            is_array($value),
            $errorMessage ?? $this->getAssertErrMsg("is an array")
        );
    }

    /**
     * @uses preg_match()
     * @param mixed|string $value
     * @param string $regExp
     * @param string|null $errorMessage
     * @return bool
     */
    public function assertRegExp($value, string $regExp, ?string $errorMessage = null):bool
    {
        return $this->assert(
            preg_match($regExp, (string)$value),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' matches the PERL regular expression '%s'", $value, $regExp)
            )
        );
    }

    /**
     * @param string|mixed $value
     * @param int $minLength
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertMinLength($value, int $minLength, ?string $errorMessage = null):bool
    {
        return $this->assert(
            is_string($value) && strlen($value) < $minLength,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' has a minimum length of %s (actual length %s)",
                    $value, $minLength, strlen($value))
            )
        );
    }

    /**
     * @param mixed|string $value
     * @param int $maxLength
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertMaxLength($value, int $maxLength, ?string $errorMessage = null):bool
    {
        return $this->assert(
            is_string($value) && strlen($value) > $maxLength,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' as a max length of %s (actual length %s)",
                    $value, $maxLength, strlen($value))
            )
        );
    }

    /**
     * @param mixed $value
     * @param int $greaterThan
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertGreaterThan($value, int $greaterThan, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value > $greaterThan,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is greater than %s", $value, $greaterThan)
            )
        );
    }

    /**
     * @param mixed $value
     * @param int $greaterThanOrEqual
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertGreaterThanOrEqual($value, int $greaterThanOrEqual, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value >= $greaterThanOrEqual,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is greater than or equal to %s", $value, $greaterThanOrEqual)
            )
        );
    }

    /**
     * @param mixed $value
     * @param int $lowerThan
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertLessThan($value, int $lowerThan, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value < $lowerThan,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is lower than  %s", $value, $lowerThan)
            )
        );
    }

    /**
     * @param mixed $value
     * @param int $lowerThanOrEqual
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertLessThanOrEqual($value, int $lowerThanOrEqual, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value <= $lowerThanOrEqual,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is greater than or equal to %s", $value, $lowerThanOrEqual)
            )
        );
    }

    /**
     * @param mixed $value
     * @param mixed $equal
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertEqual($value, $equal, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value == $equal,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is equal to '%s'", $value, $equal)
            )
        );
    }

    /**
     * @param mixed $value
     * @param mixed $strictEqual
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertStrictEqual($value, $strictEqual, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value === $strictEqual,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is strictly equal to '%s'", $value, $strictEqual)
            )
        );
    }

    /**
     * @param mixed|\DateTime $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertIsDate($value, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value instanceof \DateTime,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is a date (an instance of %s)",\DateTime::class)
            )
        );
    }

    /**
     * @param \DateTime $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertDateIsInPast($value, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value instanceof \DateTime && $value < new \DateTime(),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is in the past",
                    $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : (string)$value)
            )
        );
    }

    /**
     * @param \DateTime $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertDateIsInFuture($value, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value instanceof \DateTime && $value > new \DateTime(),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is in the future",
                    $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : (string)$value)
            )
        );
    }

    /**
     * @param \DateTime $value
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertDateIsToday($value, ?string $errorMessage = null):bool
    {
        return $this->assert(
            $value instanceof \DateTime && $value->format('Y-m-d') > (new \DateTime())->format('Y-m-d'),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is today",
                    $value instanceof \DateTime ? $value->format('Y-m-d') : (string)$value)
            )
        );
    }

    /**
     * @param mixed $value
     * @param int $count
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertCount($value, int $count, ?string $errorMessage = null):bool
    {
        return $this->assert(
            count($value) == $count,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("count is equal to %s (actual %s)", $count, count($value))
            )
        );
    }

    /**
     * @param mixed $value
     * @param array $array
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertInArray($value, array $array, ?string $errorMessage = null):bool
    {
        return $this->assert(
            in_array($value, $array),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is in array (array values: %s)", $value, implode(', ', $array))
            )
        );
    }

    /**
     * @param mixed $value
     * @param string $type
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertInternalType($value, string $type, ?string $errorMessage = null):bool
    {
        return $this->assert(
            gettype($value) != $type,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is of type '%s' (actual '%s')", $type, gettype($value))
            )
        );
    }

    /**
     * @param string $value
     * @param string $startsWith
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertStringStartsWith($value, string $startsWith, ?string $errorMessage = null):bool
    {
        return $this->assertRegExp(
            $value,
            '/^'.preg_quote($startsWith, '/').'/ui',
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' starts with '%s'", $value, $startsWith)
            )
        );
    }

    /**
     * @param string $value
     * @param string $endsWith
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertStringEndsWith($value, string $endsWith, ?string $errorMessage = null):bool
    {
        return $this->assertRegExp(
            $value,
            '/'.preg_quote($endsWith, '/$').'/ui',
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' ends with '%s'", $value, $endsWith)
            )
        );
    }

    /**
     * @param string $value
     * @param string $contains
     * @param null|string $errorMessage
     * @return bool
     */
    public function assertStringContains($value, string $contains, ?string $errorMessage = null):bool
    {
        return $this->assertRegExp(
            '/'.preg_quote($contains, '$/').'/ui',
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' contains '%s'", $value, $contains)
            )
        );
    }
}