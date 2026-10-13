<?php

namespace Aternos\SpigotApi\Tests\Unit\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Tests\TestCase;
use Composer\InstalledVersions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

class SpigotAPIClientTest extends TestCase
{
    public function testGetLibraryVersionReturnsInstalledVersion(): void
    {
        $this->assertEquals(
            InstalledVersions::getPrettyVersion(SpigotAPIClient::PACKAGE_NAME),
            SpigotAPIClient::getLibraryVersion()
        );
        $this->assertNotEquals("unknown", SpigotAPIClient::getLibraryVersion());
    }

    public function testDefaultUserAgentContainsPackageAndVersion(): void
    {
        $this->assertEquals(
            "aternos/php-spigot-api/" . SpigotAPIClient::getLibraryVersion(),
            SpigotAPIClient::getDefaultUserAgent()
        );
    }

    /**
     * @throws ApiException
     */
    public function testDefaultUserAgentIsSentWithRequests(): void
    {
        $history = [];
        $client = new SpigotAPIClient(null, $this->createHttpClient($history));

        $client->getProject(2);

        $this->assertEquals(SpigotAPIClient::getDefaultUserAgent(), $history[0]['request']->getHeaderLine("User-Agent"));
    }

    /**
     * @throws ApiException
     */
    public function testSetUserAgentReplacesDefaultUserAgent(): void
    {
        $history = [];
        $client = new SpigotAPIClient(null, $this->createHttpClient($history));

        $this->assertSame($client, $client->setUserAgent("test-agent/1.0"));
        $client->getProject(2);

        $this->assertEquals("test-agent/1.0", $history[0]['request']->getHeaderLine("User-Agent"));
    }

    /**
     * Create an HTTP client that answers with the getResource fixture and records all requests
     *
     * @param array $history
     * @return Client
     */
    protected function createHttpClient(array &$history): Client
    {
        $handlerStack = HandlerStack::create(new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/getResource.json")),
        ]));
        $handlerStack->push(Middleware::history($history));
        return new Client(['handler' => $handlerStack]);
    }
}
