<?php


namespace Kelunik\Regex;

use Countable;

/**
 * Result of a multi match.
 */
class MatchResult implements Countable {
    /** @var array */
    private $matches;

    /**
     * @param array $matches Raw PHP PCRE matches array including offset capturing.
     */
    public function __construct(array $matches) {
        $this->matches = array_map(function ($match) {
            return new Match($match);
        }, $matches);
    }

    /**
     * @return int Returns the total number of matches.
     */
    public function count(): int {
        return count($this->matches);
    }

    /**
     * @return Match[] Returns all matches.
     */
    public function matches(): array {
        return $this->matches;
    }
}