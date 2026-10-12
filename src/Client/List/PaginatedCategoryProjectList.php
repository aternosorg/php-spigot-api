<?php

namespace Aternos\SpigotApi\Client\List;

use Aternos\SpigotApi\Client\Category;
use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\ProjectOrder;
use Aternos\SpigotApi\Client\SortDirection;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Model\Resource;

/**
 * @extends PaginatedList<Project>
 */
class PaginatedCategoryProjectList extends PaginatedList
{

    protected SpigotAPIClient $client;
    protected Category $category;
    protected ?ProjectOrder $order;
    protected ?SortDirection $direction;

    /**
     * @param SpigotAPIClient $client
     * @param Category $category
     * @param int $page
     * @param Resource[] $resources
     * @param ProjectOrder|null $order
     * @param SortDirection|null $direction
     */
    public function __construct(
        SpigotAPIClient $client,
        Category        $category,
        int             $page,
        array           $resources,
        ?ProjectOrder   $order = null,
        ?SortDirection  $direction = null,
    )
    {
        $this->client = $client;
        $this->category = $category;
        $this->order = $order;
        $this->direction = $direction;

        $hits = count($resources);
        $projects = array_map(fn(Resource $resource) => new Project($this->client, $resource), $resources);

        parent::__construct($page, $hits, $projects);
    }

    public function getPage(int $page): static
    {
        return $this->client->listProjectsForCategory($this->category, $page, $this->order, $this->direction);
    }
}
