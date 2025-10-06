<?php

namespace Aternos\SpigotApi\Tests\Unit\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\Version;
use Aternos\SpigotApi\Model\ResourceUpdate;
use Aternos\SpigotApi\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class VersionTest extends TestCase
{
    protected function getResourceUpdateModel(): ResourceUpdate
    {
        return new ResourceUpdate([
            'id' => 227294,
            'resource_id' => 57242,
            'resource_version' => '1.0.0',
            'download_count' => 449,
            'title' => 'spark',
            'message' => '[CENTER][B][SIZE=7][IMG]https://i.imgur.com/ykHn9vx.png[/IMG]   [/SIZE][/B]
[SIZE=4][B]spark is a performance profiler for Minecraft clients, servers and proxies.[/B][/SIZE]
[/CENTER]
[B]Useful Links[/B]
[LIST]
[*][B][URL=\'https://spark.lucko.me/\']Website[/URL][/B] - browse the project homepage
[*][B][URL=\'https://spark.lucko.me/docs\']Documentation[/URL][/B] - read documentation and usage guides
[*][B][URL=\'https://spark.lucko.me/download\']Downloads[/URL][/B] - latest development builds
[/LIST]
[B][SIZE=5]What does it do?[/SIZE][/B]
spark is made up of a number of components, each detailed separately below.
[LIST]
[*][B]CPU Profiler[/B]: Diagnose performance issues.
[*][B]Memory Inspection[/B]: Diagnose memory issues.
[*][B]Server Health Reporting[/B]: Keep track of overall server health.
[/LIST]

[B][SIZE=6]⚡ CPU Profiler[/SIZE][/B]
spark\'s profiler can be used to diagnose performance issues: "lag", low tick rate, high CPU usage, etc.'
        ]);
    }

    public function testGetProjectId(): void
    {
        $version = new Version($this->apiClient, $this->getResourceUpdateModel());
        $this->assertEquals(57242, $version->getProjectId());
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
        $version = new Version($this->apiClient, $this->getResourceUpdateModel());

        $project = $version->getProject();
        $this->assertNotNull($project);
        $this->assertEquals(57242, $project->getData()->getId());
        $this->assertEquals("spark", $project->getData()->getTitle());
    }

}