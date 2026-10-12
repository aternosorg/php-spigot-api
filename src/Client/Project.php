<?php

namespace Aternos\SpigotApi\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\List\PaginatedVersionList;
use Aternos\SpigotApi\Model\Resource;

class Project
{

    public function __construct(
        protected SpigotAPIClient $client,
        protected Resource        $data
    )
    {
    }

    public function getData(): Resource
    {
        return $this->data;
    }

    /**
     * Get the ID of the project.
     *
     * @return int
     */
    public function getId(): int
    {
        return intval($this->data->getId());
    }

    /**
     * Get the category of the project.
     *
     * @return Category
     */
    public function getCategory(): Category
    {
        return Category::from($this->getData()->getCategory()->getId());
    }

    /**
     * Get the author of the project.
     *
     * @throws ApiException
     */
    public function getAuthor(): Author
    {
        return $this->client->getAuthor($this->data->getAuthor()->getId());
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
