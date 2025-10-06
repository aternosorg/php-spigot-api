<?php

namespace Aternos\SpigotApi\Client\List;

use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Client\Version;
use Aternos\SpigotApi\Model\ResourceUpdate;

/**
 * @extends PaginatedList<Version>
 */
class PaginatedVersionList extends PaginatedList
{

    protected SpigotAPIClient $client;
    protected int $projectId;

    public function __construct(SpigotAPIClient $client, int $projectId, int $page, array $updates)
    {
        $this->client = $client;
        $this->projectId = $projectId;

        $hits = count($updates);
        $versions = array_map(fn(ResourceUpdate $update) => new Version($this->client, $update), $updates);

        parent::__construct($page, $hits, $versions);
    }

    public function getPage(int $page): static
    {
        return $this->client->getProjectVersions($this->projectId, $page);
    }
}