<?php

namespace Aternos\SpigotApi\Client\List;

use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\ProjectOrder;
use Aternos\SpigotApi\Client\SortDirection;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Model\Resource;

/**
 * @extends PaginatedList<Project>
 */
class PaginatedProjectList extends PaginatedList
{

    protected SpigotAPIClient $client;
    protected ?ProjectOrder $order;
    protected ?SortDirection $direction;

    /**
     * @param SpigotAPIClient $client
     * @param int $page
     * @param Resource[] $resources
     * @param ProjectOrder|null $order
     * @param SortDirection|null $direction
     */
    public function __construct(
        SpigotAPIClient $client,
        int             $page,
        array           $resources,
        ?ProjectOrder   $order = null,
        ?SortDirection  $direction = null,
    )
    {
        $this->client = $client;
        $this->order = $order;
        $this->direction = $direction;

        $hits = count($resources);
        $projects = array_map(fn(Resource $resource) => new Project($this->client, $resource), $resources);

        parent::__construct($page, $hits, $projects);
    }

    public function getPage(int $page): static
    {
        return $this->client->listProjects($page, $this->order, $this->direction);
    }
}
