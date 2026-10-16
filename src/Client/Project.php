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
     * @deprecated The project extends the generated model now, so its getters can be used directly.
     * @return Resource
     */
    public function getData(): Resource
    {
        return $this;
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
     * Fetch the author of the project from the API.
     *
     * @return Author
     * @throws ApiException
     */
    public function fetchAuthor(): Author
    {
        return $this->client->getAuthor($this->getAuthor()->getId());
    }

    /**
     * Get a list of versions for this project.
     *
     * @param int $page
     * @param VersionOrder|null $order Field to order the results by. When null, the API default (id) is used.
     * @param SortDirection|null $direction Direction to order the results by. When null, the API default (asc) is used.
     * @throws ApiException
     */
    public function getVersions(
        int            $page = 1,
        ?VersionOrder  $order = null,
        ?SortDirection $direction = null,
    ): PaginatedVersionList
    {
        return $this->client->getProjectVersions($this->getId(), $page, $order, $direction);
    }
}
