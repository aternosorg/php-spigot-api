<?php

namespace Aternos\SpigotApi\Tests\Unit\Client\List;

use Aternos\SpigotApi\Client\List\PaginatedProjectList;
use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Model\Resource;
use Aternos\SpigotApi\Tests\TestCase;

class PaginatedProjectListTest extends TestCase
{
    public function testConstructionTransformsResourcesToProjects(): void
    {
        $resources = [
            new Resource(['id' => 1]),
            new Resource(['id' => 2]),
            new Resource(['id' => 3]),
        ];

        $list = new PaginatedProjectList($this->apiClient, 1, $resources);

        $this->assertCount(3, $list);
        $this->assertSame(3, $list->getHits());
        $this->assertInstanceOf(Project::class, $list[0]);
        $this->assertInstanceOf(Project::class, $list[1]);
        $this->assertInstanceOf(Project::class, $list[2]);
    }

    public function testArrayAccessAndIteration(): void
    {
        $resources = [
            new Resource(['id' => 10]),
            new Resource(['id' => 11]),
        ];

        $list = new PaginatedProjectList($this->apiClient, 2, $resources);

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

        $page1 = new PaginatedProjectList($mockedApiClient, 1, [new Resource(['id' => 1])]);
        $page2 = new PaginatedProjectList($mockedApiClient, 2, [new Resource(['id' => 2])]);

        $mockedApiClient->expects($this->once())
            ->method('listProjects')
            ->with(2)
            ->willReturn($page2);

        $next = $page1->getNextPage();
        $this->assertSame($page2, $next);
    }

    public function testGetPreviousPage(): void
    {
        $mockedApiClient = $this->createMock(SpigotAPIClient::class);

        $page1 = new PaginatedProjectList($mockedApiClient, 1, [new Resource(['id' => 1])]);
        $this->assertFalse($page1->hasPreviousPage());
        $this->assertNull($page1->getPreviousPage());

        $page2 = new PaginatedProjectList($mockedApiClient, 2, [new Resource(['id' => 2])]);

        $mockedApiClient->expects($this->once())
            ->method('listProjects')
            ->with(1)
            ->willReturn($page1);

        $previous = $page2->getPreviousPage();
        $this->assertSame($page1, $previous);
    }

    public function testGetResultsFromFollowingPagesAggregatesUntilEmptyPage(): void
    {
        $mockedApiClient = $this->createMock(SpigotAPIClient::class);

        $page1 = new PaginatedProjectList($mockedApiClient, 1, [new Resource(['id' => 10])]);
        $page2 = new PaginatedProjectList($mockedApiClient, 2, [
            new Resource(['id' => 20]),
            new Resource(['id' => 21]),
        ]);
        // Empty page (hits = 0) stops the aggregation loop
        $page3 = new PaginatedProjectList($mockedApiClient, 3, []);

        $mockedApiClient->expects($this->exactly(2))
            ->method('listProjects')
            ->willReturnOnConsecutiveCalls($page2, $page3);

        $all = $page1->getResultsFromFollowingPages();
        $this->assertCount(3, $all); // 1 from page1 + 2 from page2
        $this->assertContainsOnlyInstancesOf(Project::class, $all);
    }
}