<?php

namespace Aternos\SpigotApi\Tests\Unit\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\Category;
use Aternos\SpigotApi\Client\ProjectOrder;
use Aternos\SpigotApi\Client\SortDirection;
use Aternos\SpigotApi\Client\VersionOrder;
use Aternos\SpigotApi\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Query;
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
            $this->assertEquals(Category::SPIGOT->value, $project->getCategory()->getId());
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
        $this->assertEquals("spark", $project->getTitle());
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
        $this->assertEquals("spark", $version->getTitle());
        $this->assertEquals(227294, $version->getId());
        $this->assertEquals(57242, $version->getResourceId());
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
        $this->assertEquals("Luck", $user->getUsername());
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
        $this->assertEquals("Luck", $user->getUsername());
        $this->assertEquals(100356, $user->getId());
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
            $this->assertEquals(100356, $project->getAuthor()->getId());
        }
    }

    /**
     * @throws ApiException
     */
    public function testListProjectsWithoutOrderDoesNotSendOrderParameters(): void
    {
        $history = [];
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/listResources.json")),
        ]);
        $handlerStack = HandlerStack::create($handler);
        $handlerStack->push(Middleware::history($history));
        $this->apiClient->setHttpClient(new Client(['handler' => $handlerStack]));

        $this->apiClient->listProjects();

        $request = $history[0]['request'];
        $queryParameters = Query::parse($request->getUri()->getQuery());
        $this->assertArrayNotHasKey("order", $queryParameters);
        $this->assertArrayNotHasKey("direction", $queryParameters);
    }

    /**
     * @throws ApiException
     */
    public function testListProjectsWithOrder(): void
    {
        $history = [];
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/listResources.json")),
        ]);
        $handlerStack = HandlerStack::create($handler);
        $handlerStack->push(Middleware::history($history));
        $this->apiClient->setHttpClient(new Client(['handler' => $handlerStack]));

        $projectList = $this->apiClient->listProjects(2, ProjectOrder::DOWNLOAD_COUNT, SortDirection::DESC);
        $this->assertValidPaginatedList($projectList);

        $request = $history[0]['request'];
        $queryParameters = Query::parse($request->getUri()->getQuery());
        $this->assertEquals("2", $queryParameters["page"]);
        $this->assertEquals("download_count", $queryParameters["order"]);
        $this->assertEquals("desc", $queryParameters["direction"]);
    }

    /**
     * @throws ApiException
     */
    public function testListProjectsForCategoryWithOrder(): void
    {
        $history = [];
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/listResourcesInSpigotCategory.json")),
        ]);
        $handlerStack = HandlerStack::create($handler);
        $handlerStack->push(Middleware::history($history));
        $this->apiClient->setHttpClient(new Client(['handler' => $handlerStack]));

        $projectList = $this->apiClient->listProjectsForCategory(Category::SPIGOT, 1, ProjectOrder::LAST_UPDATE, SortDirection::ASC);
        $this->assertValidPaginatedList($projectList);

        $request = $history[0]['request'];
        $queryParameters = Query::parse($request->getUri()->getQuery());
        $this->assertEquals(Category::SPIGOT->value, $queryParameters["category"]);
        $this->assertEquals("last_update", $queryParameters["order"]);
        $this->assertEquals("asc", $queryParameters["direction"]);
    }

    /**
     * @throws ApiException
     */
    public function testGetProjectVersionsWithOrder(): void
    {
        $history = [];
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/getResourceUpdates.json")),
        ]);
        $handlerStack = HandlerStack::create($handler);
        $handlerStack->push(Middleware::history($history));
        $this->apiClient->setHttpClient(new Client(['handler' => $handlerStack]));

        $versions = $this->apiClient->getProjectVersions(2, 1, VersionOrder::POST_DATE, SortDirection::DESC);
        $this->assertValidPaginatedList($versions);

        $request = $history[0]['request'];
        $queryParameters = Query::parse($request->getUri()->getQuery());
        $this->assertEquals("2", $queryParameters["id"]);
        $this->assertEquals("post_date", $queryParameters["order"]);
        $this->assertEquals("desc", $queryParameters["direction"]);
    }
}
