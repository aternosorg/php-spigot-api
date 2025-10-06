<?php

namespace Aternos\SpigotApi\Tests\Unit\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\Category;
use Aternos\SpigotApi\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ClientTest extends TestCase
{

    /**
     * @throws ApiException
     */
    public function testListProjects(): void
    {
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/listResources.json")),
        ]);
        $this->apiClient->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));

        $projectList = $this->apiClient->listProjects(1);
        $this->assertValidPaginatedList($projectList);

        foreach ($projectList as $project) {
            $this->assertValidProject($project);
        }
    }

    /**
     * @throws ApiException
     */
    public function testGetProjectsInCategory(): void
    {
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/listResourcesInSpigotCategory.json")),
        ]);
        $this->apiClient->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));

        $projectList = $this->apiClient->listProjectsForCategory(Category::SPIGOT, 1);
        $this->assertValidPaginatedList($projectList);

        foreach ($projectList as $project) {
            $this->assertValidProject($project);
            $this->assertEquals(Category::SPIGOT->value, $project->getData()->getCategory()->getId());
        }
    }

    /**
     * @throws ApiException
     */
    public function testGetProject(): void
    {
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/getResource.json")),
        ]);
        $this->apiClient->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));

        $project = $this->apiClient->getProject(2);
        $this->assertNotNull($project);
        $this->assertValidProject($project);
        $this->assertEquals("spark", $project->getData()->getTitle());
    }

    /**
     * @throws ApiException
     */
    public function testGetVersion(): void
    {
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/getResourceUpdate.json")),
        ]);
        $this->apiClient->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));

        $version = $this->apiClient->getVersion(352711);
        $this->assertNotNull($version);
        $this->assertValidVersion($version);
        $this->assertEquals("spark", $version->getData()->getTitle());
        $this->assertEquals(227294, $version->getData()->getId());
        $this->assertEquals(57242, $version->getData()->getResourceId());
    }

    /**
     * @throws ApiException
     */
    public function testGetProjectVersions(): void
    {
        $versions = $this->apiClient->getProjectVersions(2);
        $this->assertNotNull($versions);
        $this->assertNotEmpty($versions);
        $this->assertValidPaginatedList($versions);

        foreach ($versions as $version) {
            $this->assertValidVersion($version);
            $this->assertEquals(2, $version->getProjectId());
        }
    }

    /**
     * @throws ApiException
     */
    public function testGetAuthor(): void
    {
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/getAuthor.json")),
        ]);
        $this->apiClient->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));

        $user = $this->apiClient->getAuthor(1);
        $this->assertNotNull($user);
        $this->assertNotNull($user->getData());
        $this->assertEquals("Luck", $user->getData()->getUsername());
    }

    /**
     * @throws ApiException
     */
    public function testFindAuthor(): void
    {
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/findAuthor.json")),
        ]);
        $this->apiClient->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));

        $user = $this->apiClient->findAuthor("Luck");
        $this->assertNotNull($user);
        $this->assertNotNull($user->getData());
        $this->assertEquals("Luck", $user->getData()->getUsername());
        $this->assertEquals(100356, $user->getData()->getId());
    }

    /**
     * @throws ApiException
     */
    public function testGetAuthorProjects(): void
    {
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/getResourcesByAuthor.json")),
        ]);
        $this->apiClient->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));

        $projectList = $this->apiClient->getAuthorProjects(100356);
        $this->assertNotNull($projectList);
        $this->assertNotEmpty($projectList);
        $this->assertValidPaginatedList($projectList);

        foreach ($projectList as $project) {
            $this->assertValidProject($project);
            $this->assertEquals(100356, $project->getData()->getAuthor()->getId());
        }
    }
}