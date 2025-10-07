<?php

namespace Aternos\SpigotApi\Tests\Unit\Client\List;

use Aternos\SpigotApi\Client\Category;
use Aternos\SpigotApi\Client\List\PaginatedCategoryProjectList;
use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\SpigotAPIClient;
use Aternos\SpigotApi\Model\Resource;
use Aternos\SpigotApi\Model\ResourceCategory;
use Aternos\SpigotApi\Tests\TestCase;

class PaginatedCategoryProjectListTest extends TestCase
{
    protected function createResource(int $id, Category $category = Category::SPIGOT): Resource
    {
        return new Resource([
            'id' => $id,
            'category' =>
                new ResourceCategory([
                    'id' => $category->value,
                    'title' => $category->name
                ])
        ]);
    }

    public function testConstructionTransformsResourcesToProjects(): void
    {
        $resources = [
            $this->createResource(1),
            $this->createResource(2),
            $this->createResource(3),
        ];

        $list = new PaginatedCategoryProjectList($this->apiClient, Category::SPIGOT, 1, $resources);

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

        $list = new PaginatedCategoryProjectList($this->apiClient, Category::SPIGOT, 2, $resources);

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

        $page1 = new PaginatedCategoryProjectList($mockedApiClient, Category::SPIGOT, 1, [
            $this->createResource(1, Category::SPIGOT)
        ]);
        $page2 = new PaginatedCategoryProjectList($mockedApiClient, Category::SPIGOT, 2, [
            $this->createResource(2, Category::SPIGOT)
        ]);

        $mockedApiClient->expects($this->once())
            ->method('listProjectsForCategory')
            ->with(Category::SPIGOT, 2)
            ->willReturn($page2);

        $next = $page1->getNextPage();
        $this->assertSame($page2, $next);
    }

    public function testGetPreviousPage(): void
    {
        $mockedApiClient = $this->createMock(SpigotAPIClient::class);

        $page1 = new PaginatedCategoryProjectList($mockedApiClient, Category::SPIGOT, 1, [$this->createResource(1, Category::SPIGOT)]);
        $this->assertFalse($page1->hasPreviousPage());
        $this->assertNull($page1->getPreviousPage());

        $page2 = new PaginatedCategoryProjectList($mockedApiClient, Category::SPIGOT, 2, [$this->createResource(2, Category::SPIGOT)]);

        $mockedApiClient->expects($this->once())
            ->method('listProjectsForCategory')
            ->with(Category::SPIGOT, 1)
            ->willReturn($page1);

        $previous = $page2->getPreviousPage();
        $this->assertSame($page1, $previous);
    }

    public function testGetResultsFromFollowingPagesAggregatesUntilEmptyPage(): void
    {
        $mockedApiClient = $this->createMock(SpigotAPIClient::class);

        $page1 = new PaginatedCategoryProjectList($mockedApiClient, Category::SPIGOT, 1, [
            $this->createResource(10, Category::SPIGOT),
        ]);
        $page2 = new PaginatedCategoryProjectList($mockedApiClient, Category::SPIGOT, 2, [
            $this->createResource(20, Category::SPIGOT),
            $this->createResource(21, Category::SPIGOT),
        ]);
        // Empty page (hits = 0) stops the aggregation loop
        $page3 = new PaginatedCategoryProjectList($mockedApiClient, Category::SPIGOT, 3, []);

        $mockedApiClient->expects($this->exactly(2))
            ->method('listProjectsForCategory')
            ->willReturnOnConsecutiveCalls($page2, $page3);

        $all = $page1->getResultsFromFollowingPages();
        $this->assertCount(3, $all); // 1 from page1 + 2 from page2
        $this->assertContainsOnlyInstancesOf(Project::class, $all);
    }
}