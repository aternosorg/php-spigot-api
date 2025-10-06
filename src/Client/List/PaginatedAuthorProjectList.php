<?php

namespace Aternos\SpigotApi\Client\List;

use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Model\Resource;

/**
 * @extends PaginatedList<Project>
 */
class PaginatedAuthorProjectList extends PaginatedList
{

    protected SpigotAPIClient $client;
    protected int $authorId;

    public function __construct(SpigotAPIClient $client, int $authorId, int $page, array $resources)
    {
        $this->client = $client;
        $this->authorId = $authorId;

        $hits = count($resources);
        $projects = array_map(fn(Resource $resource) => new Project($this->client, $resource), $resources);

        parent::__construct($page, $hits, $projects);
    }

    public function getPage(int $page): static
    {
        return $this->client->getAuthorProjects($this->authorId, $page);
    }
}