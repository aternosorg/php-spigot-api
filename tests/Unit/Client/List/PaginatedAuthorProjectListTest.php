<?php

namespace Aternos\SpigotApi\Tests\Unit\Client\List;

use Aternos\SpigotApi\Client\List\PaginatedAuthorProjectList;
use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Model\Resource;
use Aternos\SpigotApi\Model\ResourceAuthor;
use Aternos\SpigotApi\Tests\TestCase;

class PaginatedAuthorProjectListTest extends TestCase
{
    protected function createResource(int $id, int $authorId = 1): Resource
    {
        return new Resource([
            'id' => $id,
            'author' => new ResourceAuthor(['id' => $authorId, 'username' => 'author-' . $authorId]),
        ]);
    }

    public function testConstructionTransformsResourcesToProjects(): void
    {
        $resources = [
            $this->createResource(1),
            $this->createResource(2),
            $this->createResource(3),
        ];

        $list = new PaginatedAuthorProjectList($this->apiClient, 123, 1, $resources);

        $this->assertCount(3, $list);
        $this->assertSame(3, $list->getHits());
        $this->assertInstanceOf(Project::class, $list[0]);
        $this->assertInstanceOf(Project::class, $list[1]);
        $this->assertInstanceOf(Project::class, $list[2]);
    }

    public function testArrayAccessAndIteration(): void
    {
        $resources = [
            $this->createResource(10),
            $this->createResource(11),
        ];

        $list = new PaginatedAuthorProjectList($this->apiClient, 5, 2, $resources);

        // ArrayAccess
        $this->assertTrue(isset($list[0]));
        $this->assertTrue(isset($list[1]));
        $this->assertFalse(isset($list[2]));

        // Iteration
        $collected = [];
        foreach ($list as $project) {
            $collected[] = $project;
        }
        $this->assertCount(2, $collected);
        $this->assertEquals($list[0], $collected[0]);
        $this->assertEquals($list[1], $collected[1]);

        // Rewind + key/valid
        $list->rewind();
        $this->assertTrue($list->valid());
        $this->assertSame(0, $list->key());
        $list->next();
        $this->assertSame(1, $list->key());
    }

    public function testGetNextPageUsesClient(): void
    {
        $mockedApiClient = $this->createMock(SpigotAPIClient::class);
        $authorId = 55;

        $page1 = new PaginatedAuthorProjectList($mockedApiClient, $authorId, 1, [
            $this->createResource(1, $authorId)
        ]);
        $page2 = new PaginatedAuthorProjectList($mockedApiClient, $authorId, 2, [
            $this->createResource(2, $authorId)
        ]);

        $mockedApiClient->expects($this->once())
            ->method('getAuthorProjects')
            ->with($authorId, 2)
            ->willReturn($page2);

        $next = $page1->getNextPage();
        $this->assertSame($page2, $next);
    }

    public function testGetPreviousPage(): void
    {
        $mockedApiClient = $this->createMock(SpigotAPIClient::class);
        $authorId = 77;

        $page1 = new PaginatedAuthorProjectList($mockedApiClient, $authorId, 1, [$this->createResource(1, $authorId)]);
        $this->assertFalse($page1->hasPreviousPage());
        $this->assertNull($page1->getPreviousPage());

        $page2 = new PaginatedAuthorProjectList($mockedApiClient, $authorId, 2, [$this->createResource(2, $authorId)]);

        $mockedApiClient->expects($this->once())
            ->method('getAuthorProjects')
            ->with($authorId, 1)
            ->willReturn($page1);

        $previous = $page2->getPreviousPage();
        $this->assertSame($page1, $previous);
    }

    public function testGetResultsFromFollowingPagesAggregatesUntilEmptyPage(): void
    {
        $mockedApiClient = $this->createMock(SpigotAPIClient::class);
        $authorId = 999;

        $page1 = new PaginatedAuthorProjectList($mockedApiClient, $authorId, 1, [
            $this->createResource(10, $authorId),
        ]);
        $page2 = new PaginatedAuthorProjectList($mockedApiClient, $authorId, 2, [
            $this->createResource(20, $authorId),
            $this->createResource(21, $authorId),
        ]);
        // Empty page (hits = 0) stops the aggregation loop
        $page3 = new PaginatedAuthorProjectList($mockedApiClient, $authorId, 3, []);

        $mockedApiClient->expects($this->exactly(2))
            ->method('getAuthorProjects')
            ->willReturnOnConsecutiveCalls($page2, $page3);

        $all = $page1->getResultsFromFollowingPages();
        $this->assertCount(3, $all); // 1 from page1 + 2 from page2
        $this->assertContainsOnlyInstancesOf(Project::class, $all);
    }
}