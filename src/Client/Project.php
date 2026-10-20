<?php

namespace Aternos\SpigotApi\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\List\PaginatedVersionList;
use Aternos\SpigotApi\Model\Resource;

class Project extends Resource
{

    public function __construct(
        protected SpigotAPIClient $client,
        Resource                  $data
    )
    {
        parent::__construct($data->container);
    }

    /**
     * Get the ID of the project.
     *
     * @return int
     */
    public function getId(): int
    {
        return intval(parent::getId());
    }

    /**
     * Get the category of the project.
     *
     * @return Category
     */
    public function getCategoryType(): Category
    {
        return Category::from($this->getCategory()->getId());
    }

    /**
     * Fetch the author of this project from the API.
     *
     * This sends a request to the SpigotMC API every time it is called.
     *
     * @return Author
     * @throws ApiException
     */
    public function fetchAuthor(): Author
    {
        return $this->client->getAuthor($this->getAuthor()->getId());
    }

    /**
     * Fetch the versions of this project from the API (paginated).
     *
     * This sends a request to the SpigotMC API every time it is called. The result is not cached.
     *
     * @param int $page
     * @param VersionOrder|null $order Field to order the results by. When null, the API default (id) is used.
     * @param SortDirection|null $direction Direction to order the results by. When null, the API default (asc) is used.
     * @return PaginatedVersionList
     * @throws ApiException
     */
    public function fetchVersions(
        int            $page = 1,
        ?VersionOrder  $order = null,
        ?SortDirection $direction = null,
    ): PaginatedVersionList
    {
        return $this->client->getProjectVersions($this->getId(), $page, $order, $direction);
    }

    /**
     * Fetch the versions of this project from the API (paginated).
     *
     * This sends a request to the SpigotMC API every time it is called. The result is not cached.
     *
     * @deprecated Use {@see fetchVersions()} instead. Methods that send a request to the API are prefixed with "fetch".
     * @param int $page
     * @param VersionOrder|null $order Field to order the results by. When null, the API default (id) is used.
     * @param SortDirection|null $direction Direction to order the results by. When null, the API default (asc) is used.
     * @return PaginatedVersionList
     * @throws ApiException
     */
    public function getVersions(
        int            $page = 1,
        ?VersionOrder  $order = null,
        ?SortDirection $direction = null,
    ): PaginatedVersionList
    {
        return $this->fetchVersions($page, $order, $direction);
    }
}
