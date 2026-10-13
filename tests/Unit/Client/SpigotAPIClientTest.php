<?php

namespace Aternos\SpigotApi\Tests\Unit\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Configuration;
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
     * Clients created without an explicit configuration must not share one,
     * otherwise changing the user agent of one client would change it for all others.
     */
    public function testClientsWithoutExplicitConfigurationDoNotShareOne(): void
    {
        $defaultUserAgent = Configuration::getDefaultConfiguration()->getUserAgent();

        $first = new SpigotAPIClient();
        $second = new SpigotAPIClient();
        $first->setUserAgent("first-agent/1.0");

        $this->assertEquals("first-agent/1.0", $this->getConfiguration($first)->getUserAgent());
        $this->assertEquals(SpigotAPIClient::getDefaultUserAgent(), $this->getConfiguration($second)->getUserAgent());
        $this->assertEquals($defaultUserAgent, Configuration::getDefaultConfiguration()->getUserAgent());
    }

    public function testExplicitConfigurationIsUsed(): void
    {
        $configuration = new Configuration();
        $client = new SpigotAPIClient($configuration);

        $client->setUserAgent("custom-agent/1.0");

        $this->assertSame($configuration, $this->getConfiguration($client));
        $this->assertEquals("custom-agent/1.0", $configuration->getUserAgent());
    }

    protected function getConfiguration(SpigotAPIClient $client): Configuration
    {
        return (new \ReflectionProperty($client, "configuration"))->getValue($client);
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
