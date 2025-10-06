<?php

namespace Aternos\SpigotApi\Tests\Integration\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\Version;
use Aternos\SpigotApi\Tests\TestCase;

class VersionTest extends TestCase
{
    protected ?Version $version = null;

    /**
     * @throws ApiException
     */
    public function setUp(): void
    {
        parent::setUp();
        // 2.10.9 of PlaceholderAPI
        $this->version = $this->apiClient->getVersion(352711);
    }

    public function testGetProjectId(): void
    {
        // 6245 -> PlaceholderAPI
        $this->assertEquals(6245, $this->version->getProjectId());
    }

    /**
     * @throws ApiException
     */
    public function testGetProject(): void
    {
        $project = $this->apiClient->getProject(6245);
        $this->assertEquals($project, $this->version->getProject());
    }

}