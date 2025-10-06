<?php

namespace Aternos\SpigotApi\Client\List;

use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Model\Resource;

/**
 * @extends PaginatedList<Project>
 */
class PaginatedProjectList extends PaginatedList
{

    protected SpigotAPIClient $client;

    public function __construct(SpigotAPIClient $client, int $page, array $resources)
    {
        $this->client = $client;

        $hits = count($resources);
        $projects = array_map(fn(Resource $resource) => new Project($this->client, $resource), $resources);

        parent::__construct($page, $hits, $projects);
    }

    public function getPage(int $page): static
    {
        return $this->client->listProjects($page);
    }
}