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
        $this->apiClient->setUserAgent("aternos/php-spigot-api@1.0.0 (contact@aternos.org)");
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
        $this->assertNotNull($project->getData());

        $this->assertNotNull($project->getData()->getStats());
        $this->assertValidStats($project->getData()->getStats());

        $this->assertNotNull($project->getData()->getAuthor());
        $this->assertValidResourceAuthor($project->getData()->getAuthor());

        $this->assertNotNull($project->getData()->getId());
        $this->assertNotNull($project->getData()->getTitle());
        $this->assertNotNull($project->getData()->getTag());
        $this->assertNotNull($project->getData()->getCurrentVersion());
        // $project->getData()->getNativeMinecraftVersion() can be null
        // $this->assertNotNull($project->getData()->getSupportedMinecraftVersions()) can be null or empty
        $this->assertNotNull($project->getData()->getIconLink());
        $this->assertNotNull($project->getData()->getExternalDownloadUrl());
        $this->assertNotNull($project->getData()->getDescription());
        $this->assertNotNull($project->getData()->getFirstRelease());
        $this->assertNotNull($project->getData()->getLastUpdate());
    }

    protected function assertValidVersion(Version $version): void
    {
        $this->assertNotNull($version->getId());
        $this->assertNotNull($version->getProjectId());
        $this->assertNotNull($version->getData()->getId());
        $this->assertNotNull($version->getData()->getResourceId());
        $this->assertNotNull($version->getData()->getDownloadCount());
        $this->assertNotNull($version->getData()->getTitle());
        $this->assertNotNull($version->getData()->getMessage());
    }

    protected function assertValidPaginatedList(PaginatedList $list): void
    {
        $this->assertNotEmpty($list);
        $this->assertNotNull($list->getResults());
        $this->assertNotEmpty($list->getResults());
    }

}