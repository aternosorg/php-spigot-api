<?php

namespace Aternos\SpigotApi\Client\List;

use Aternos\SpigotApi\Client\Category;
use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Model\Resource;

/**
 * @extends PaginatedList<Project>
 */
class PaginatedCategoryProjectList extends PaginatedList
{

    protected SpigotAPIClient $client;
    protected Category $category;

    public function __construct(SpigotAPIClient $client, Category $category, int $page, array $resources)
    {
        $this->client = $client;
        $this->category = $category;

        $hits = count($resources);
        $projects = array_map(fn(Resource $resource) => new Project($this->client, $resource), $resources);

        parent::__construct($page, $hits, $projects);
    }

    public function getPage(int $page): static
    {
        return $this->client->listProjectsForCategory($this->category, $page);
    }
}