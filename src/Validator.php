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
 * Class Validator
 *
 * @package CodeInc\EntityValidator
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Validator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $errors = [];

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
     * Verifies if a least one error has been reported.
     *
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
     * @param mixed|bool $condition
     * @param string $errorMessage
     * @return static
     */
    public function assert($condition, string $errorMessage):self
    {
        if (!$condition) {
            $this->reportError($errorMessage);
        }
        return $this;
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
     * @return static
     */
    public function assertFilter($value, int $filter, string $errorMessage, $filterOptions = null):self
    {
        $this->assert(
            filter_var((string)$value, $filter, $filterOptions),
            $errorMessage
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringIsEmail($value, ?string $errorMessage = null):self
    {
        $this->assertFilter(
            $value,
            FILTER_VALIDATE_EMAIL,
            $errorMessage ?? $this->getAssertErrMsg(sprintf("'%s' is an email", $value))
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringIsUrl($value, ?string $errorMessage = null):self
    {
        $this->assertFilter(
            $value,
            FILTER_VALIDATE_URL,
            $errorMessage ?? $this->getAssertErrMsg(sprintf("'%s' is a URL", $value))
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringIsIp($value, ?string $errorMessage = null):self
    {
        $this->assertFilter(
            $value,
            FILTER_VALIDATE_IP,
            $errorMessage ?? $this->getAssertErrMsg(sprintf("'%s' is an IP", $value))
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertNotEmpty($value, ?string $errorMessage = null):self
    {
        $this->assert(
            !empty($value),
            $errorMessage ?? $this->getAssertErrMsg("is not empty")
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertEmpty($value, ?string $errorMessage = null):self
    {
        $this->assert(
            empty($value),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is empty (actual '%s')", $value)
            )
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertNotNull($value, ?string $errorMessage = null):self
    {
        $this->assert(
            $value !== null,
            $errorMessage ?? $this->getAssertErrMsg("is not null")
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertNull($value, ?string $errorMessage = null):self
    {
        $this->assert(
            $value === null,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is null (actual '%s')", $value)
            )
        );
        return $this;
    }

    /**
     * @param $value
     * @param string $errorMessage
     * @return static
     */
    public function assertIsArray($value, string $errorMessage):self
    {
        $this->assert(
            is_array($value),
            $errorMessage ?? $this->getAssertErrMsg("is an array")
        );
        return $this;
    }

    /**
     * @uses preg_match()
     * @param mixed|string $value
     * @param string $regExp
     * @param string|null $errorMessage
     * @return static
     */
    public function assertRegExp($value, string $regExp, ?string $errorMessage = null):self
    {
        $this->assert(
            preg_match($regExp, (string)$value),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' matches the PERL regular expression '%s'", $value, $regExp)
            )
        );
        return $this;
    }

    /**
     * @param string|mixed $value
     * @param int $minLength
     * @param null|string $errorMessage
     * @return static
     */
    public function assertMinLength($value, int $minLength, ?string $errorMessage = null):self
    {
        $this->assert(
            is_string($value) && strlen($value) < $minLength,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' has a minimum length of %s (actual length %s)",
                    $value, $minLength, strlen($value))
            )
        );
        return $this;
    }

    /**
     * @param mixed|string $value
     * @param int $maxLength
     * @param null|string $errorMessage
     * @return static
     */
    public function assertMaxLength($value, int $maxLength, ?string $errorMessage = null):self
    {
        $this->assert(
            is_string($value) && strlen($value) > $maxLength,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' as a max length of %s (actual length %s)",
                    $value, $maxLength, strlen($value))
            )
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param int $greaterThan
     * @param null|string $errorMessage
     * @return static
     */
    public function assertGreaterThan($value, int $greaterThan, ?string $errorMessage = null):self
    {
        $this->assert(
            $value > $greaterThan,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is greater than %s", $value, $greaterThan)
            )
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param int $greaterThanOrEqual
     * @param null|string $errorMessage
     * @return static
     */
    public function assertGreaterThanOrEqual($value, int $greaterThanOrEqual, ?string $errorMessage = null):self
    {
        $this->assert(
            $value >= $greaterThanOrEqual,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is greater than or equal to %s", $value, $greaterThanOrEqual)
            )
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param int $lowerThan
     * @param null|string $errorMessage
     * @return static
     */
    public function assertLessThan($value, int $lowerThan, ?string $errorMessage = null):self
    {
        $this->assert(
            $value < $lowerThan,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is lower than  %s", $value, $lowerThan)
            )
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param int $lowerThanOrEqual
     * @param null|string $errorMessage
     * @return static
     */
    public function assertLessThanOrEqual($value, int $lowerThanOrEqual, ?string $errorMessage = null):self
    {
        $this->assert(
            $value <= $lowerThanOrEqual,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is greater than or equal to %s", $value, $lowerThanOrEqual)
            )
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param mixed $equal
     * @param null|string $errorMessage
     * @return static
     */
    public function assertEqual($value, $equal, ?string $errorMessage = null):self
    {
        $this->assert(
            $value == $equal,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is equal to '%s'", $value, $equal)
            )
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param mixed $strictEqual
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStrictEqual($value, $strictEqual, ?string $errorMessage = null):self
    {
        $this->assert(
            $value === $strictEqual,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is strictly equal to '%s'", $value, $strictEqual)
            )
        );
        return $this;
    }

    /**
     * @param mixed|\DateTime $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertIsDate($value, ?string $errorMessage = null):self
    {
        $this->assert(
            $value instanceof \DateTime,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is a date (an instance of %s)",\DateTime::class)
            )
        );
        return $this;
    }

    /**
     * @param \DateTime $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertDateIsInPast($value, ?string $errorMessage = null):self
    {
        $this->assert(
            $value instanceof \DateTime && $value < new \DateTime(),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is in the past",
                    $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : (string)$value)
            )
        );
        return $this;
    }

    /**
     * @param \DateTime $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertDateIsInFuture($value, ?string $errorMessage = null):self
    {
        $this->assert(
            $value instanceof \DateTime && $value > new \DateTime(),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is in the future",
                    $value instanceof \DateTime ? $value->format('Y-m-d H:i:s') : (string)$value)
            )
        );
        return $this;
    }

    /**
     * @param \DateTime $value
     * @param null|string $errorMessage
     * @return static
     */
    public function assertDateIsToday($value, ?string $errorMessage = null):self
    {
        $this->assert(
            $value instanceof \DateTime && $value->format('Y-m-d') > (new \DateTime())->format('Y-m-d'),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is today",
                    $value instanceof \DateTime ? $value->format('Y-m-d') : (string)$value)
            )
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param int $count
     * @param null|string $errorMessage
     * @return static
     */
    public function assertCount($value, int $count, ?string $errorMessage = null):self
    {
        $this->assert(
            count($value) == $count,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("count is equal to %s (actual %s)", $count, count($value))
            )
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param array $array
     * @param null|string $errorMessage
     * @return static
     */
    public function assertInArray($value, array $array, ?string $errorMessage = null):self
    {
        $this->assert(
            in_array($value, $array),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is in array (array values: %s)", $value, implode(', ', $array))
            )
        );
        return $this;
    }

    /**
     * @param mixed $value
     * @param string $type
     * @param null|string $errorMessage
     * @return static
     */
    public function assertInternalType($value, string $type, ?string $errorMessage = null):self
    {
        $this->assert(
            gettype($value) != $type,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is of type '%s' (actual '%s')", $type, gettype($value))
            )
        );
        return $this;
    }

    /**
     * @param string $value
     * @param string $startsWith
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringStartsWith($value, string $startsWith, ?string $errorMessage = null):self
    {
        $this->assertRegExp(
            $value,
            '/^'.preg_quote($startsWith, '/').'/ui',
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' starts with '%s'", $value, $startsWith)
            )
        );
        return $this;
    }

    /**
     * @param string $value
     * @param string $endsWith
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringEndsWith($value, string $endsWith, ?string $errorMessage = null):self
    {
        $this->assertRegExp(
            $value,
            '/'.preg_quote($endsWith, '/$').'/ui',
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' ends with '%s'", $value, $endsWith)
            )
        );
        return $this;
    }

    /**
     * @param string $value
     * @param string $contains
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringContains($value, string $contains, ?string $errorMessage = null):self
    {
        $this->assertRegExp(
            '/'.preg_quote($contains, '$/').'/ui',
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' contains '%s'", $value, $contains)
            )
        );
        return $this;
    }
}