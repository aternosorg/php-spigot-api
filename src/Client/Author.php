<?php

namespace Aternos\SpigotApi\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\List\PaginatedAuthorProjectList;
use Aternos\SpigotApi\Model\Author as AuthorModel;

class Author extends AuthorModel
{

    public function __construct(
        protected SpigotAPIClient $client,
        AuthorModel               $data
    )
    {
        parent::__construct($data->container);
    }

    /**
     * Get the ID of the author.
     *
     * @return int
     */
    public function getId(): int
    {
        return intval(parent::getId());
    }

    /**
     * Fetch the projects of this author from the API (paginated).
     *
     * This sends a request to the SpigotMC API every time it is called. The result is not cached.
     *
     * @return PaginatedAuthorProjectList
     * @throws ApiException
     */
    public function fetchProjects(): PaginatedAuthorProjectList
    {
        return $this->client->getAuthorProjects($this->getId());
    }

    /**
     * Fetch the projects of this author from the API (paginated).
     *
     * This sends a request to the SpigotMC API every time it is called. The result is not cached.
     *
     * @deprecated Use {@see fetchProjects()} instead. Methods that send a request to the API are prefixed with "fetch".
     * @return PaginatedAuthorProjectList
     * @throws ApiException
     */
    public function getProjects(): PaginatedAuthorProjectList
    {
        return $this->fetchProjects();
    }
}
