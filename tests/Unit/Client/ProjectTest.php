<?php

namespace Aternos\SpigotApi\Tests\Unit\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Model\Resource;
use Aternos\SpigotApi\Model\ResourceAuthor;
use Aternos\SpigotApi\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ProjectTest extends TestCase
{
    protected function getResourceModel(): Resource
    {
        $author = new ResourceAuthor([
            'id' => 100356,
            'username' => 'Luck'
        ]);

        return new Resource([
            'id' => 57242,
            'title' => 'spark',
            'tag' => 'spark is a performance profiler for Minecraft clients, servers and proxies.',
            'current_version' => '1.10.119',
            'category' => [
                'id' => 15,
                'title' => 'Tools and Utilities',
                'description' => ''
            ],
            'native_minecraft_version' => '',
            'supported_minecraft_versions' => [
                '1.8',
                '1.9',
                '1.10',
                '1.11',
                '1.12',
                '1.13',
                '1.14',
                '1.15',
                '1.16',
                '1.17',
                '1.18',
                '1.19',
                '1.20',
                '1.20.5',
                '1.21'
            ],
            'icon_link' => 'https://www.spigotmc.org/data/resource_icons/57/57242.jpg?1645211170',
            'author' => $author,
            'premium' => [
                'price' => '0.00',
                'currency' => ''
            ],
            'stats' => [
                'downloads' => 246550,
                'updates' => 21,
                'reviews' => [
                    'unique' => 91,
                    'total' => 103
                ],
                'rating' => '4.8'
            ],
            'external_download_url' => '',
            'first_release' => 1527527040,
            'last_update' => 1733070687,
            'description' => '[CENTER][B][SIZE=7][IMG]https://i.imgur.com/ykHn9vx.png[/IMG]   [/SIZE][/B]
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

    /**
     * @throws ApiException
     */
    public function testGetAuthor(): void
    {
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/getAuthor.json")),
        ]);
        $this->apiClient->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));
        $project = new Project($this->apiClient, $this->getResourceModel());

        $author = $project->getAuthor();
        $this->assertNotNull($author);
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
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/getResourceUpdates.json")),
        ]);
        $this->apiClient->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));
        $project = new Project($this->apiClient, $this->getResourceModel());

        $versions = $project->getVersions();
        $this->assertNotNull($versions);
        $this->assertNotEmpty($versions);

        foreach ($versions as $version) {
            $this->assertValidVersion($version);
            $this->assertEquals($project->getId(), $version->getProjectId());
        }
    }


}