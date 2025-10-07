<?php

namespace Aternos\SpigotApi\Tests\Unit\Client\List;

use Aternos\SpigotApi\Client\List\PaginatedVersionList;
use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Client\Version;
use Aternos\SpigotApi\Model\ResourceUpdate;
use Aternos\SpigotApi\Tests\TestCase;

class PaginatedVersionListTest extends TestCase
{
    public function testConstructionTransformsResourceUpdatesToVersions(): void
    {
        $resourceUpdates = [
            new ResourceUpdate(['id' => 1, 'resource_id' => 100]),
            new ResourceUpdate(['id' => 2, 'resource_id' => 100]),
            new ResourceUpdate(['id' => 3, 'resource_id' => 100]),
        ];

        $list = new PaginatedVersionList($this->apiClient, 100, 1, $resourceUpdates);

        $this->assertCount(3, $list);
        $this->assertSame(3, $list->getHits());
        $this->assertInstanceOf(Version::class, $list[0]);
        $this->assertInstanceOf(Version::class, $list[1]);
        $this->assertInstanceOf(Version::class, $list[2]);
    }

    public function testArrayAccessAndIteration(): void
    {
        $resourceUpdates = [
            new ResourceUpdate(['id' => 1, 'resource_id' => 100]),
            new ResourceUpdate(['id' => 2, 'resource_id' => 100]),
        ];

        $list = new PaginatedVersionList($this->apiClient, 100, 2, $resourceUpdates);

        // ArrayAccess
        $this->assertTrue(isset($list[0]));
        $this->assertTrue(isset($list[1]));
        $this->assertFalse(isset($list[2]));

        // Iteration
        $collected = [];
        foreach ($list as $version) {
            $collected[] = $version;
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

        $page1 = new PaginatedVersionList($mockedApiClient, 100, 1, [new ResourceUpdate(['id' => 1, 'resource_id' => 100])]);
        $page2 = new PaginatedVersionList($mockedApiClient, 100, 2, [new ResourceUpdate(['id' => 2, 'resource_id' => 100])]);

        $mockedApiClient->expects($this->once())
            ->method('getProjectVersions')
            ->with(100, 2)
            ->willReturn($page2);

        $next = $page1->getNextPage();
        $this->assertSame($page2, $next);
    }

    public function testGetPreviousPage(): void
    {
        $mockedApiClient = $this->createMock(SpigotAPIClient::class);

        $page1 = new PaginatedVersionList($mockedApiClient, 100, 1, [new ResourceUpdate(['id' => 1, 'resource_id' => 100])]);
        $this->assertFalse($page1->hasPreviousPage());
        $this->assertNull($page1->getPreviousPage());

        $page2 = new PaginatedVersionList($mockedApiClient, 100, 2, [new ResourceUpdate(['id' => 2, 'resource_id' => 100])]);

        $mockedApiClient->expects($this->once())
            ->method('getProjectVersions')
            ->with(100, 1)
            ->willReturn($page1);

        $previous = $page2->getPreviousPage();
        $this->assertSame($page1, $previous);
    }

    public function testGetResultsFromFollowingPagesAggregatesUntilEmptyPage(): void
    {
        $mockedApiClient = $this->createMock(SpigotAPIClient::class);

        $page1 = new PaginatedVersionList($mockedApiClient, 100, 1, [new ResourceUpdate(['id' => 10, 'resource_id' => 100])]);
        $page2 = new PaginatedVersionList($mockedApiClient, 100, 2, [
            new ResourceUpdate(['id' => 20, 'resource_id' => 100]),
            new ResourceUpdate(['id' => 21, 'resource_id' => 100]),
        ]);
        // Empty page (hits = 0) stops the aggregation loop
        $page3 = new PaginatedVersionList($mockedApiClient, 100, 3, []);

        $mockedApiClient->expects($this->exactly(2))
            ->method('getProjectVersions')
            ->willReturnOnConsecutiveCalls($page2, $page3);

        $all = $page1->getResultsFromFollowingPages();
        $this->assertCount(3, $all); // 1 from page1 + 2 from page2
        $this->assertContainsOnlyInstancesOf(Version::class, $all);
    }
}