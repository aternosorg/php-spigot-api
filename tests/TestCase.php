<?php

namespace Aternos\SpigotApi\Tests;

use Aternos\SpigotApi\Client\List\PaginatedList;
use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Client\Version;
use Aternos\SpigotApi\Model\Author;
use Aternos\SpigotApi\Model\ResourceAuthor;
use Aternos\SpigotApi\Model\ResourceCategory;
use Aternos\SpigotApi\Model\ResourceStats;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{

    protected ?SpigotAPIClient $apiClient = null;

    /**
     * Setup before running each test case
     */
    public function setUp(): void
    {
        $this->apiClient = new SpigotAPIClient();
        $this->apiClient->setUserAgent(SpigotAPIClient::getDefaultUserAgent() . " (contact@aternos.org)");
    }

    protected function assertValidStats(ResourceStats $stats): void
    {
        $this->assertNotNull($stats->getDownloads());
        $this->assertNotNull($stats->getUpdates());
        $this->assertNotNull($stats->getReviews());
        $this->assertNotNull($stats->getRating());
    }

    protected function assertValidAuthor(Author $author): void
    {
        $this->assertNotNull($author->getId());
        $this->assertNotNull($author->getUsername());
        $this->assertNotNull($author->getResourceCount());
        $this->assertNotNull($author->getIdentities());
        $this->assertNotNull($author->getAvatar());
        $this->assertNotNull($author->getLastActivity());
    }

    protected function assertValidResourceAuthor(ResourceAuthor $author): void
    {
        $this->assertNotNull($author->getId());
        $this->assertNotNull($author->getUsername());
    }

    protected function assertValidCategory(ResourceCategory $category): void
    {
        $this->assertNotNull($category->getId());
        $this->assertNotNull($category->getTitle());
        $this->assertNotNull($category->getDescription());
    }

    protected function assertValidProject(Project $project): void
    {
        $this->assertNotNull($project->getStats());
        $this->assertValidStats($project->getStats());

        $this->assertNotNull($project->getAuthor());
        $this->assertValidResourceAuthor($project->getAuthor());

        $this->assertNotNull($project->getId());
        $this->assertNotNull($project->getTitle());
        $this->assertNotNull($project->getTag());
        $this->assertNotNull($project->getCurrentVersion());
        // $project->getNativeMinecraftVersion() can be null
        // $this->assertNotNull($project->getSupportedMinecraftVersions()) can be null or empty
        $this->assertNotNull($project->getIconLink());
        $this->assertNotNull($project->getExternalDownloadUrl());
        $this->assertNotNull($project->getDescription());
        $this->assertNotNull($project->getFirstRelease());
        $this->assertNotNull($project->getLastUpdate());
        $this->assertNotNull($project->getSourceCodeUrl());
        $this->assertNotNull($project->getDonateUrl());
    }

    protected function assertValidVersion(Version $version): void
    {
        $this->assertNotNull($version->getId());
        $this->assertNotNull($version->getProjectId());
        $this->assertNotNull($version->getId());
        $this->assertNotNull($version->getResourceId());
        $this->assertNotNull($version->getDownloadCount());
        $this->assertNotNull($version->getTitle());
        $this->assertNotNull($version->getMessage());
        $this->assertNotNull($version->getPostDate());
    }

    protected function assertValidPaginatedList(PaginatedList $list): void
    {
        $this->assertNotEmpty($list);
        $this->assertNotNull($list->getResults());
        $this->assertNotEmpty($list->getResults());
    }

}
