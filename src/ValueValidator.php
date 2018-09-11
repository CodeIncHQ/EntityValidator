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
     * @var mixed
     */
    private $value;

    /**
     * ValueValidator constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
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
     * @param int $filter
     * @param string $errorMessage
     * @param null $filterOptions
     * @return static
     */
    public function assertFilter(int $filter, string $errorMessage, $filterOptions = null):self
    {
        $this->assert(
            filter_var((string)$this->value, $filter, $filterOptions),
            $errorMessage
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringIsEmail(?string $errorMessage = null):self
    {
        $this->assertFilter(
            FILTER_VALIDATE_EMAIL,
            $errorMessage ?? $this->getAssertErrMsg(sprintf("'%s' is an email", $this->value))
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringIsUrl(?string $errorMessage = null):self
    {
        $this->assertFilter(
            FILTER_VALIDATE_URL,
            $errorMessage ?? $this->getAssertErrMsg(sprintf("'%s' is a URL", $this->value))
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringIsIp(?string $errorMessage = null):self
    {
        $this->assertFilter(
            FILTER_VALIDATE_IP,
            $errorMessage ?? $this->getAssertErrMsg(sprintf("'%s' is an IP", $this->value))
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertNotEmpty(?string $errorMessage = null):self
    {
        $this->assert(
            !empty($this->value),
            $errorMessage ?? $this->getAssertErrMsg("is not empty")
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertEmpty(?string $errorMessage = null):self
    {
        $this->assert(
            empty($this->value),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is empty (actual '%s')", $this->value)
            )
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertNotNull(?string $errorMessage = null):self
    {
        $this->assert(
            $this->value !== null,
            $errorMessage ?? $this->getAssertErrMsg("is not null")
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertNull(?string $errorMessage = null):self
    {
        $this->assert(
            $this->value === null,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is null (actual '%s')", $this->value)
            )
        );
        return $this;
    }

    /**
     * @param string $errorMessage
     * @return static
     */
    public function assertIsArray(string $errorMessage):self
    {
        $this->assert(
            is_array($this->value),
            $errorMessage ?? $this->getAssertErrMsg("is an array")
        );
        return $this;
    }

    /**
     * @uses preg_match()
     * @param string $regExp
     * @param string|null $errorMessage
     * @return static
     */
    public function assertRegExp(string $regExp, ?string $errorMessage = null):self
    {
        $this->assert(
            preg_match($regExp, (string)$this->value),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' matches the PERL regular expression '%s'", $this->value, $regExp)
            )
        );
        return $this;
    }

    /**
     * @param int $minLength
     * @param null|string $errorMessage
     * @return static
     */
    public function assertMinLength(int $minLength, ?string $errorMessage = null):self
    {
        $this->assert(
            is_string($this->value) && strlen($this->value) < $minLength,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' has a minimum length of %s (actual length %s)",
                    $this->value, $minLength, strlen($this->value))
            )
        );
        return $this;
    }

    /**
     * @param int $maxLength
     * @param null|string $errorMessage
     * @return static
     */
    public function assertMaxLength(int $maxLength, ?string $errorMessage = null):self
    {
        $this->assert(
            is_string($this->value) && strlen($this->value) > $maxLength,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' as a max length of %s (actual length %s)",
                    $this->value, $maxLength, strlen($this->value))
            )
        );
        return $this;
    }

    /**
     * @param int $greaterThan
     * @param null|string $errorMessage
     * @return static
     */
    public function assertGreaterThan(int $greaterThan, ?string $errorMessage = null):self
    {
        $this->assert(
            $this->value > $greaterThan,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is greater than %s", $this->value, $greaterThan)
            )
        );
        return $this;
    }

    /**
     * @param int $greaterThanOrEqual
     * @param null|string $errorMessage
     * @return static
     */
    public function assertGreaterThanOrEqual(int $greaterThanOrEqual, ?string $errorMessage = null):self
    {
        $this->assert(
            $this->value >= $greaterThanOrEqual,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is greater than or equal to %s", $this->value, $greaterThanOrEqual)
            )
        );
        return $this;
    }

    /**
     * @param int $lowerThan
     * @param null|string $errorMessage
     * @return static
     */
    public function assertLessThan(int $lowerThan, ?string $errorMessage = null):self
    {
        $this->assert(
            $this->value < $lowerThan,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is lower than  %s", $this->value, $lowerThan)
            )
        );
        return $this;
    }

    /**
     * @param int $lowerThanOrEqual
     * @param null|string $errorMessage
     * @return static
     */
    public function assertLessThanOrEqual(int $lowerThanOrEqual, ?string $errorMessage = null):self
    {
        $this->assert(
            $this->value <= $lowerThanOrEqual,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is greater than or equal to %s", $this->value, $lowerThanOrEqual)
            )
        );
        return $this;
    }

    /**
     * @param mixed $equal
     * @param null|string $errorMessage
     * @return static
     */
    public function assertEqual($equal, ?string $errorMessage = null):self
    {
        $this->assert(
            $this->value == $equal,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is equal to '%s'", $this->value, $equal)
            )
        );
        return $this;
    }

    /**
     * @param mixed $strictEqual
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStrictEqual($strictEqual, ?string $errorMessage = null):self
    {
        $this->assert(
            $this->value === $strictEqual,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is strictly equal to '%s'", $this->value, $strictEqual)
            )
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertIsDate(?string $errorMessage = null):self
    {
        $this->assert(
            $this->value instanceof \DateTime,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is a date (an instance of %s)",\DateTime::class)
            )
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertDateIsInPast(?string $errorMessage = null):self
    {
        $this->assert(
            $this->value instanceof \DateTime && $this->value < new \DateTime(),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is in the past",
                    $this->value instanceof \DateTime ? $this->value->format('Y-m-d H:i:s') : (string)$this->value)
            )
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertDateIsInFuture(?string $errorMessage = null):self
    {
        $this->assert(
            $this->value instanceof \DateTime && $this->value > new \DateTime(),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is in the future",
                    $this->value instanceof \DateTime ? $this->value->format('Y-m-d H:i:s') : (string)$this->value)
            )
        );
        return $this;
    }

    /**
     * @param null|string $errorMessage
     * @return static
     */
    public function assertDateIsToday(?string $errorMessage = null):self
    {
        $this->assert(
            $this->value instanceof \DateTime && $this->value->format('Y-m-d') > (new \DateTime())->format('Y-m-d'),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is today",
                    $this->value instanceof \DateTime ? $this->value->format('Y-m-d') : (string)$this->value)
            )
        );
        return $this;
    }

    /**
     * @param int $count
     * @param null|string $errorMessage
     * @return static
     */
    public function assertCount(int $count, ?string $errorMessage = null):self
    {
        $this->assert(
            count($this->value) == $count,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("count is equal to %s (actual %s)", $count, count($this->value))
            )
        );
        return $this;
    }

    /**
     * @param array $array
     * @param null|string $errorMessage
     * @return static
     */
    public function assertInArray(array $array, ?string $errorMessage = null):self
    {
        $this->assert(
            in_array($this->value, $array),
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' is in array (array values: %s)", $this->value, implode(', ', $array))
            )
        );
        return $this;
    }

    /**
     * @param string $type
     * @param null|string $errorMessage
     * @return static
     */
    public function assertInternalType(string $type, ?string $errorMessage = null):self
    {
        $this->assert(
            gettype($this->value) != $type,
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("is of type '%s' (actual '%s')", $type, gettype($this->value))
            )
        );
        return $this;
    }

    /**
     * @param string $startsWith
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringStartsWith(string $startsWith, ?string $errorMessage = null):self
    {
        $this->assertRegExp(
            '/^'.preg_quote($startsWith, '/').'/ui',
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' starts with '%s'", $this->value, $startsWith)
            )
        );
        return $this;
    }

    /**
     * @param string $endsWith
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringEndsWith(string $endsWith, ?string $errorMessage = null):self
    {
        $this->assertRegExp(
            '/'.preg_quote($endsWith, '/$').'/ui',
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' ends with '%s'", $this->value, $endsWith)
            )
        );
        return $this;
    }

    /**
     * @param string $contains
     * @param null|string $errorMessage
     * @return static
     */
    public function assertStringContains(string $contains, ?string $errorMessage = null):self
    {
        $this->assertRegExp(
            '/'.preg_quote($contains, '$/').'/ui',
            $errorMessage ?? $this->getAssertErrMsg(
                sprintf("'%s' contains '%s'", $this->value, $contains)
            )
        );
        return $this;
    }
}