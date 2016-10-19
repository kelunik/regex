<?php

namespace Kelunik\Regex;

/**
 * Sane regular expressions.
 */
class Regex {
    /**
     * Test whether a subject matches a given pattern.
     *
     * @param string $pattern Regular expression.
     * @param string $subject Subject to check.
     *
     * @return bool `true` if the pattern matches the subject, `false` otherwise.
     *
     * @throws InvalidRegularExpressionError If the pattern is invalid.
     */
    public static function test(string $pattern, string $subject): bool {
        $count = preg_match($pattern, $subject);

        if ($count === false) {
            throw new InvalidRegularExpressionError(self::getErrorMessage(preg_last_error()));
        }

        return (bool) $count;
    }

    /**
     * Matches a pattern once and returns the matched groups.
     *
     * @param string $pattern Regular expression.
     * @param string $subject Subject to match.
     * @param int    $offset Offset to start from.
     *
     * @return Match|null The match or `null` if there's none.
     *
     * @throws InvalidRegularExpressionError If the pattern is invalid.
     */
    public static function matchOne(string $pattern, string $subject, int $offset = 0) {
        $count = preg_match($pattern, $subject, $match, PREG_OFFSET_CAPTURE, $offset);

        if ($count === false) {
            throw new InvalidRegularExpressionError(self::getErrorMessage(preg_last_error()));
        }

        if ($count === 0) {
            return null;
        }

        return new Match($match);
    }

    /**
     * Matches a pattern as often as possible and returns the matched groups.
     *
     * @param string $pattern Regular expression.
     * @param string $subject Subject to match.
     * @param int    $offset Offset to start from.
     *
     * @return MatchResult
     *
     * @throws InvalidRegularExpressionError If the pattern is invalid.
     */
    public static function matchAll(string $pattern, string $subject, int $offset = 0): MatchResult {
        $count = preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE, $offset);

        if ($count === false) {
            throw new InvalidRegularExpressionError(self::getErrorMessage(preg_last_error()));
        }

        return new MatchResult($matches);
    }

    /**
     * Filter an array of strings using regular expressions.
     *
     * @param string $pattern Regular expression.
     * @param array  $values Array of subjects to match.
     * @param bool   $invert `true` to return elements that do not match, `false` to return elements that match (default).
     *
     * @return array Filtered input array.
     *
     * @throws InvalidRegularExpressionError If the pattern is invalid.
     */
    public static function grep(string $pattern, array $values, bool $invert = false): array {
        $result = preg_grep($pattern, $values, $invert ? PREG_GREP_INVERT : 0);

        if ($result === false) {
            throw new InvalidRegularExpressionError(self::getErrorMessage(preg_last_error()));
        }

        return $result;
    }

    /**
     * Escapes special regular expression characters by prepending a backslash.
     *
     * Delimiter is a required argument as it's often forgotten when using the native PHP function, it MUST always be
     * passed.
     *
     * @param string $str String to escape.
     * @param string $delimiter Used delimiter in the pattern.
     *
     * @return string Escaped string.
     *
     * @see Regex::quoteDelimiter()
     * @see http://php.net/manual/de/function.preg-quote.php
     */
    public static function quote(string $str, string $delimiter): string {
        return preg_quote($str, $delimiter);
    }

    /**
     * Escapes only the delimiter instead of all regular expression characters.
     *
     * This might be useful if you construct a regular expression of multiple subpatterns stored in constants. Those
     * subpatterns don't have any delimiters yet, so they have to be added and escaped in the subpattern.
     *
     * @param string $str String to escape.
     * @param string $delimiter Used delimiter in the pattern.
     *
     * @return string Escaped string.
     *
     * @see Regex::quote()
     */
    public static function quoteDelimiter(string $str, string $delimiter): string {
        return str_replace($delimiter, "\\" . $delimiter, $str);
    }

    private static function getErrorMessage(int $errorCode): string {
        static $errorMessages;

        if (!isset($errorMessages)) {
            $errorMessages = [];
            $constants = get_defined_constants(true);

            foreach ($constants["pcre"] as $name => $value) {
                if (preg_match("~_ERROR$~", $name)) {
                    $errorMessages[$value] = $name;
                }
            }
        }

        return $errorMessages[$errorCode] ?? "Unknown error";
    }
}