<?php


namespace Kelunik\Regex;

/**
 * Single match.
 */
class Match {
    /** @var array */
    private $match;

    /**
     * @param array $match Raw PHP PCRE match array.
     */
    public function __construct(array $match) {
        $this->match = $match;
    }

    /**
     * Gets a captured group's value. Returns `null` if the group does not exist to allow default values using `??`
     * in PHP 7.0+.
     *
     * @param int|string $nameOrIndex Capturing group name or index. `0` returns the full match.
     *
     * @return string|null Matched group or `null` if the group does not exist.
     */
    public function group($nameOrIndex) {
        return $this->match[$nameOrIndex][0] ?? null;
    }

    /**
     * Checks whether a given capturing group exists.
     *
     * @param int|string $nameOrIndex Capturing group name or index. `0` returns the full match.
     *
     * @return bool `true` if the group exists, `false` otherwise.
     */
    public function groupExists($nameOrIndex): bool {
        return isset($this->match[$nameOrIndex]);
    }

    /**
     * Gets a captured group's offset. Returns `null` if the group does not exist to allow default values using `??`
     * in PHP 7.0+.
     *
     * @param int|string $nameOrIndex Capturing group name or index. `0` returns the full match.
     *
     * @return int|null The group's offset in the subject or `null` if the group does not exist.
     */
    public function groupOffset($nameOrIndex) {
        return $this->match[$nameOrIndex][1] ?? null;
    }
}