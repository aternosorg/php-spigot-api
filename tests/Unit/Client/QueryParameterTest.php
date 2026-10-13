<?php

namespace Aternos\SpigotApi\Tests\Unit\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\ProjectOrder;
use Aternos\SpigotApi\Client\SortDirection;
use Aternos\SpigotApi\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Response;

class QueryParameterTest extends TestCase
{
    /**
     * The endpoints of the Spigot API are defined as "/index.php?action=...", so all other
     * query parameters have to be appended with "&". The generated code uses "?", which is
     * patched in src/Api/DefaultApi.php. This test fails if the patch gets lost when the
     * code is regenerated.
     *
     * @throws ApiException
     */
    public function testQueryParametersAreAppendedToTheActionParameter(): void
    {
        $history = [];
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/listResources.json")),
        ]));
        $handlerStack->push(Middleware::history($history));
        $this->apiClient->setHttpClient(new Client(['handler' => $handlerStack]));

        $this->apiClient->listProjects(2, ProjectOrder::DOWNLOAD_COUNT, SortDirection::DESC);

        $uri = $history[0]['request']->getUri();
        $this->assertSame(1, substr_count((string)$uri, "?"));
        $this->assertEquals([
            "action" => "listResources",
            "page" => "2",
            "order" => "download_count",
            "direction" => "desc",
        ], Query::parse($uri->getQuery()));
    }
}
