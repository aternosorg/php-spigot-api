<?php

namespace Aternos\SpigotApi\Client\List;

use Aternos\SpigotApi\Client\SortDirection;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Client\Version;
use Aternos\SpigotApi\Client\VersionOrder;
use Aternos\SpigotApi\Model\ResourceUpdate;

/**
 * @extends PaginatedList<Version>
 */
class PaginatedVersionList extends PaginatedList
{

    protected SpigotAPIClient $client;
    protected int $projectId;
    protected ?VersionOrder $order;
    protected ?SortDirection $direction;

    public function __construct(
        SpigotAPIClient $client,
        int             $projectId,
        int             $page,
        array           $updates,
        ?VersionOrder   $order = null,
        ?SortDirection  $direction = null,
    )
    {
        $this->client = $client;
        $this->projectId = $projectId;
        $this->order = $order;
        $this->direction = $direction;

        $hits = count($updates);
        $versions = array_map(fn(ResourceUpdate $update) => new Version($this->client, $update), $updates);

        parent::__construct($page, $hits, $versions);
    }

    public function getPage(int $page): static
    {
        return $this->client->getProjectVersions($this->projectId, $page, $this->order, $this->direction);
    }
}