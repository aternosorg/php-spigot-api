<?php

namespace Aternos\SpigotApi\Client\List;


use ArrayAccess;
use Aternos\SpigotApi\ApiException;
use Countable;
use Iterator;

/**
 * @template T
 */
abstract class PaginatedList implements Iterator, ArrayAccess, Countable
{
    protected int $iterator = 0;

    protected function __construct(
        protected int   $page,
        protected int   $hits,
        /**
         * @var T[]
         */
        protected array $results,
    )
    {
    }

    /**
     * @return T[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Returns the number of hits (number of results) on this page.
     *
     * @return int
     */
    public function getHits(): int
    {
        return $this->hits;
    }

    /**
     * Get a new page of results starting from the given page.
     *
     * @param int $page
     * @return $this
     * @throws ApiException
     */
    public abstract function getPage(int $page): static;

    /**
     * Get the next page
     *
     * @return static
     * @throws ApiException
     */
    public function getNextPage(): static
    {
        return $this->getPage($this->getNextPageNum());
    }

    /**
     * get the offset of the next page
     * @return int
     */
    protected function getNextPageNum(): int
    {
        return $this->page + 1;
    }

    /**
     * returns true if there is a previous page with results on it
     * @return bool
     */
    public function hasPreviousPage(): bool
    {
        return $this->page > 1;
    }

    /**
     * Get the previous page
     * Returns null if there is no previous page
     *
     * @return static|null
     * @throws ApiException
     */
    public function getPreviousPage(): ?static
    {
        if (!$this->hasPreviousPage()) {
            return null;
        }

        return $this->getPage($this->page - 1);
    }

    /**
     * Get all results from this page and all following pages.
     * This will request each page from the api one by one.
     *
     * When called on the first page this will return all results.
     *
     * @return T[]
     * @throws ApiException
     */
    public function getResultsFromFollowingPages(): array
    {
        $results = $this->getResults();
        $nextPage = $this->getNextPage();
        while ($nextPage->getHits() > 0) {
            array_push($results, ...$nextPage->getResults());
            $nextPage = $nextPage->getNextPage();
        }
        return $results;
    }

    /**
     * @inheritDoc
     * @return T
     */
    public function current(): mixed
    {
        return $this->results[$this->iterator];
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->iterator++;
    }

    /**
     * @inheritDoc
     * @return int
     */
    public function key(): int
    {
        return $this->iterator;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return array_key_exists($this->iterator, $this->results);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->iterator = 0;
    }

    /**
     * @inheritDoc
     * @param int $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->results[$offset]);
    }

    /**
     * @inheritDoc
     * @param int $offset
     * @return T
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->results[$offset];
    }

    /**
     * @inheritDoc
     * @param int $offset
     * @param T $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->results[$offset] = $value;
    }

    /**
     * @inheritDoc
     * @param int $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->results[$offset]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->results);
    }
}