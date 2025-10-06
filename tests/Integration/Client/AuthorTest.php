<?php

namespace Aternos\SpigotApi\Tests\Integration\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\Author;
use Aternos\SpigotApi\Tests\TestCase;

class AuthorTest extends TestCase
{
    protected ?Author $author = null;

    /**
     * @throws ApiException
     */
    public function setUp(): void
    {
        parent::setUp();
        // Luck
        $this->author = $this->apiClient->getAuthor(100356);
    }

    /**
     * @throws ApiException
     */
    public function testGetProjects(): void
    {
        $projects = $this->author->getProjects();
        $this->assertNotNull($projects);
        $this->assertNotEmpty($projects);

        foreach ($projects as $project) {
            $this->assertValidProject($project);
            $this->assertEquals($this->author->getId(), $project->getAuthor()->getId());
        }
    }

}