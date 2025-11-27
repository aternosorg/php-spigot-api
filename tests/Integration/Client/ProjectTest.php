<?php

namespace Aternos\SpigotApi\Tests\Integration\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\Category;
use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Tests\TestCase;

class ProjectTest extends TestCase
{
    protected ?Project $project = null;

    /**
     * @throws ApiException
     */
    public function setUp(): void
    {
        parent::setUp();
        // Spark by Luck
        $this->project = $this->apiClient->getProject(57242);
    }

    public function testGetCategory(): void
    {
        $resourceCategory = $this->project->getData()->getCategory();
        $this->assertEquals(15, $resourceCategory->getId());
        $this->assertEquals("Tools and Utilities", $resourceCategory->getTitle());
        $this->assertEquals("", $resourceCategory->getDescription());

        $category = $this->project->getCategory();
        $this->assertEquals(Category::SPIGOT_TOOLS_AND_UTILITIES, $category);
    }

    /**
     * @throws ApiException
     */
    public function testGetAuthor(): void
    {
        // Luck
        $author = $this->project->getAuthor();
        $this->assertNotNull($author);
        $this->assertValidAuthor($author->getData());

        $this->assertEquals(100356, $author->getData()->getId());
        $this->assertEquals("Luck", $author->getData()->getUsername());
        $this->assertGreaterThan(1, $author->getData()->getResourceCount());
        $this->assertStringStartsWith("https://www.spigotmc.org/data/avatars", $author->getData()->getAvatar());
        // 1759349337 -> Wed Oct 01 2025
        $this->assertGreaterThanOrEqual(1759349337, $author->getData()->getLastActivity());
    }

    /**
     * @throws ApiException
     */
    public function testGetVersions(): void
    {
        $versions = $this->project->getVersions();
        $this->assertNotNull($versions);
        $this->assertNotEmpty($versions);

        foreach ($versions as $version) {
            $this->assertValidVersion($version);
            $this->assertEquals($this->project->getId(), $version->getProjectId());
        }
    }

}
