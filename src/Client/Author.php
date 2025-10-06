<?php

namespace Aternos\SpigotApi\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\List\PaginatedAuthorProjectList;
use Aternos\SpigotApi\Model\Author as SpigotAuthor;

class Author
{

    public function __construct(
        protected SpigotAPIClient $client,
        protected SpigotAuthor    $data
    )
    {
    }

    public function getData(): SpigotAuthor
    {
        return $this->data;
    }

    /**
     * Get the ID of the author.
     *
     * @return int
     */
    public function getId(): int
    {
        return intval($this->data->getId());
    }

    /**
     * Get a list of projects by this author.
     *
     * @throws ApiException
     */
    public function getProjects(): PaginatedAuthorProjectList
    {
        return $this->client->getAuthorProjects($this->getId());
    }
}