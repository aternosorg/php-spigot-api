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
     * @deprecated The author extends the generated model now, so its getters can be used directly.
     * @return AuthorModel
     */
    public function getData(): AuthorModel
    {
        return $this;
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
     * Get a list of projects by this author.
     *
     * @throws ApiException
     */
    public function getProjects(): PaginatedAuthorProjectList
    {
        return $this->client->getAuthorProjects($this->getId());
    }
}
