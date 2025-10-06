<?php

namespace Aternos\SpigotApi\Tests\Unit\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\Author;
use Aternos\SpigotApi\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class AuthorTest extends TestCase
{
    protected function getAuthorModel(): \Aternos\SpigotApi\Model\Author
    {
        return new \Aternos\SpigotApi\Model\Author([
            "id" => 100356,
            "username" => "Luck",
            "resource_count" => 4,
            "identities" => [],
            "avatar" => "https://www.spigotmc.org/data/avatars/l/100/100356.jpg?1655500321",
            "last_activity" => 1759349337
        ]);
    }

    /**
     * @throws ApiException
     */
    public function testGetProjects(): void
    {
        $handler = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . "/Fixtures/getResourcesByAuthor.json")),
        ]);
        $this->apiClient->setHttpClient(new Client(['handler' => HandlerStack::create($handler)]));
        $author = new Author($this->apiClient, $this->getAuthorModel());

        $projects = $author->getProjects();
        $this->assertNotNull($projects);
        $this->assertNotEmpty($projects);

        foreach ($projects as $project) {
            $this->assertValidProject($project);
            $this->assertEquals($author->getId(), $project->getData()->getAuthor()->getId());
        }
    }

}