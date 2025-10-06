<?php

namespace Aternos\SpigotApi\Tests\Integration\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\Category;
use Aternos\SpigotApi\Client\List\PaginatedAuthorProjectList;
use Aternos\SpigotApi\Client\List\PaginatedCategoryProjectList;
use Aternos\SpigotApi\Client\List\PaginatedProjectList;
use Aternos\SpigotApi\Client\List\PaginatedVersionList;
use Aternos\SpigotApi\Client\Project;
use Aternos\SpigotApi\Client\Version;
use Aternos\SpigotApi\Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

class ClientTest extends TestCase
{
    public function testListProjects(): void
    {
        $paginatedProjectList = $this->apiClient->listProjects();
        $this->assertFalse($paginatedProjectList->hasPreviousPage());

        $firstProjectOfPages = [];
        for ($i = 0; $i < 3; $i++) {
            $this->assertInstanceOf(PaginatedProjectList::class, $paginatedProjectList);
            $this->assertNotEmpty($paginatedProjectList->getResults());
            $this->assertGreaterThan(1, $paginatedProjectList->getHits());

            $firstProjectOfPages[$i] = $paginatedProjectList[0];

            foreach ($paginatedProjectList as $project) {
                $this->assertNotNull($project);
                $this->assertInstanceOf(Project::class, $project);
            }

            $paginatedProjectList = $paginatedProjectList->getNextPage();
        }

        for ($i = 2; $i >= 0; $i--) {
            $this->assertTrue($paginatedProjectList->hasPreviousPage());
            $paginatedProjectList = $paginatedProjectList->getPreviousPage();

            $this->assertInstanceOf(PaginatedProjectList::class, $paginatedProjectList);
            $this->assertNotEmpty($paginatedProjectList->getResults());
            $this->assertGreaterThan(1, $paginatedProjectList->getHits());

            $this->assertEquals($firstProjectOfPages[$i]->getId(), $paginatedProjectList[0]->getId());

            foreach ($paginatedProjectList as $project) {
                $this->assertNotNull($project);
                $this->assertInstanceOf(Project::class, $project);
            }
        }
        $this->assertFalse($paginatedProjectList->hasPreviousPage());
    }

    public function testGetProjectVersions(): void
    {
        $paginatedVersionList = $this->apiClient->getProjectVersions(2);
        $this->assertFalse($paginatedVersionList->hasPreviousPage());

        $firstVersionOfPages = [];
        for ($i = 0; $i < 3; $i++) {
            $this->assertInstanceOf(PaginatedVersionList::class, $paginatedVersionList);
            $this->assertNotEmpty($paginatedVersionList->getResults());
            $this->assertGreaterThan(1, $paginatedVersionList->getHits());

            $firstVersionOfPages[$i] = $paginatedVersionList[0];

            foreach ($paginatedVersionList as $version) {
                $this->assertNotNull($version);
                $this->assertInstanceOf(Version::class, $version);
            }

            $paginatedVersionList = $paginatedVersionList->getNextPage();
        }

        for ($i = 2; $i >= 0; $i--) {
            $this->assertTrue($paginatedVersionList->hasPreviousPage());
            $paginatedVersionList = $paginatedVersionList->getPreviousPage();

            $this->assertInstanceOf(PaginatedVersionList::class, $paginatedVersionList);
            $this->assertNotEmpty($paginatedVersionList->getResults());
            $this->assertGreaterThan(1, $paginatedVersionList->getHits());

            $this->assertEquals($firstVersionOfPages[$i]->getId(), $paginatedVersionList[0]->getId());

            foreach ($paginatedVersionList as $version) {
                $this->assertNotNull($version);
                $this->assertInstanceOf(Version::class, $version);
            }
        }
        $this->assertFalse($paginatedVersionList->hasPreviousPage());
    }

    public function testGetAuthorProjects(): void
    {
        $paginatedProjectList = $this->apiClient->getAuthorProjects(6643);
        $this->assertFalse($paginatedProjectList->hasPreviousPage());

        $firstProjectOfPages = [];
        for ($i = 0; $i < 3; $i++) {
            $this->assertInstanceOf(PaginatedAuthorProjectList::class, $paginatedProjectList);
            $this->assertNotEmpty($paginatedProjectList->getResults());
            $this->assertGreaterThan(1, $paginatedProjectList->getHits());

            $firstProjectOfPages[$i] = $paginatedProjectList[0];

            foreach ($paginatedProjectList as $project) {
                $this->assertNotNull($project);
                $this->assertInstanceOf(Project::class, $project);
            }

            $paginatedProjectList = $paginatedProjectList->getNextPage();
        }

        for ($i = 2; $i >= 0; $i--) {
            $this->assertTrue($paginatedProjectList->hasPreviousPage());
            $paginatedProjectList = $paginatedProjectList->getPreviousPage();

            $this->assertInstanceOf(PaginatedAuthorProjectList::class, $paginatedProjectList);
            $this->assertNotEmpty($paginatedProjectList->getResults());
            $this->assertGreaterThan(1, $paginatedProjectList->getHits());

            $this->assertEquals($firstProjectOfPages[$i]->getId(), $paginatedProjectList[0]->getId());

            foreach ($paginatedProjectList as $project) {
                $this->assertNotNull($project);
                $this->assertInstanceOf(Project::class, $project);
            }
        }
        $this->assertFalse($paginatedProjectList->hasPreviousPage());
    }

    #[TestWith([Category::BUNGEE_SPIGOT], Category::BUNGEE_SPIGOT->name)]
    #[TestWith([Category::BUNGEE_SPIGOT_TRANSPORTATION], Category::BUNGEE_SPIGOT_TRANSPORTATION->name)]
    #[TestWith([Category::BUNGEE_SPIGOT_CHAT], Category::BUNGEE_SPIGOT_CHAT->name)]
    #[TestWith([Category::BUNGEE_SPIGOT_TOOLS_AND_UTILITIES], Category::BUNGEE_SPIGOT_TOOLS_AND_UTILITIES->name)]
    #[TestWith([Category::BUNGEE_SPIGOT_MISC], Category::BUNGEE_SPIGOT_MISC->name)]
    #[TestWith([Category::BUNGEE_PROXY], Category::BUNGEE_PROXY->name)]
    #[TestWith([Category::BUNGEE_PROXY_LIBRARIES_AND_APIS], Category::BUNGEE_PROXY_LIBRARIES_AND_APIS->name)]
    #[TestWith([Category::BUNGEE_PROXY_TRANSPORTATION], Category::BUNGEE_PROXY_TRANSPORTATION->name)]
    #[TestWith([Category::BUNGEE_PROXY_CHAT], Category::BUNGEE_PROXY_CHAT->name)]
    #[TestWith([Category::BUNGEE_PROXY_TOOLS_AND_UTILITIES], Category::BUNGEE_PROXY_TOOLS_AND_UTILITIES->name)]
    #[TestWith([Category::BUNGEE_PROXY_MISC], Category::BUNGEE_PROXY_MISC->name)]
    #[TestWith([Category::SPIGOT], Category::SPIGOT->name)]
    #[TestWith([Category::SPIGOT_CHAT], Category::SPIGOT_CHAT->name)]
    #[TestWith([Category::SPIGOT_TOOLS_AND_UTILITIES], Category::SPIGOT_TOOLS_AND_UTILITIES->name)]
    #[TestWith([Category::SPIGOT_MISC], Category::SPIGOT_MISC->name)]
    #[TestWith([Category::SPIGOT_FUN], Category::SPIGOT_FUN->name)]
    #[TestWith([Category::SPIGOT_WORLD_MANAGEMENT], Category::SPIGOT_WORLD_MANAGEMENT->name)]
    #[TestWith([Category::SPIGOT_MECHANICS], Category::SPIGOT_MECHANICS->name)]
    #[TestWith([Category::SPIGOT_ECONOMY], Category::SPIGOT_ECONOMY->name)]
    #[TestWith([Category::SPIGOT_GAME_MODE], Category::SPIGOT_GAME_MODE->name)]
    #[TestWith([Category::SPIGOT_SKRIPT], Category::SPIGOT_SKRIPT->name)]
    #[TestWith([Category::SPIGOT_LIBRARIES_AND_APIS], Category::SPIGOT_LIBRARIES_AND_APIS->name)]
    //#[TestWith([Category::SPIGOT_NO_RATING], Category::SPIGOT_NO_RATING->name)] -> no data in this category
    #[TestWith([Category::STANDALONE], Category::STANDALONE->name)]
    #[TestWith([Category::PREMIUM], Category::PREMIUM->name)]
    #[TestWith([Category::UNIVERSAL], Category::UNIVERSAL->name)]
    #[TestWith([Category::WEB], Category::WEB->name)]
    #[TestWith([Category::TRIAL_DATA_PACK], Category::TRIAL_DATA_PACK->name)]
    public function testListProjectsForCategory(Category $category): void
    {
        $paginatedProjectList = $this->apiClient->listProjectsForCategory($category);
        $this->assertFalse($paginatedProjectList->hasPreviousPage());

        $firstProjectOfPages = [];
        for ($i = 0; $i < 3; $i++) {
            $this->assertInstanceOf(PaginatedCategoryProjectList::class, $paginatedProjectList);
            $this->assertNotEmpty($paginatedProjectList->getResults());
            $this->assertGreaterThan(1, $paginatedProjectList->getHits());

            $firstProjectOfPages[$i] = $paginatedProjectList[0];

            foreach ($paginatedProjectList as $project) {
                $this->assertNotNull($project);
                $this->assertInstanceOf(Project::class, $project);
            }

            $paginatedProjectList = $paginatedProjectList->getNextPage();
        }

        for ($i = 2; $i >= 0; $i--) {
            $this->assertTrue($paginatedProjectList->hasPreviousPage());
            $paginatedProjectList = $paginatedProjectList->getPreviousPage();

            $this->assertInstanceOf(PaginatedCategoryProjectList::class, $paginatedProjectList);
            $this->assertNotEmpty($paginatedProjectList->getResults());
            $this->assertGreaterThan(1, $paginatedProjectList->getHits());

            $this->assertEquals($firstProjectOfPages[$i]->getId(), $paginatedProjectList[0]->getId());

            foreach ($paginatedProjectList as $project) {
                $this->assertNotNull($project);
                $this->assertInstanceOf(Project::class, $project);
            }
        }
        $this->assertFalse($paginatedProjectList->hasPreviousPage());
    }

    /**
     * @throws ApiException
     */
    public function testGetProject(): void
    {
        $project = $this->apiClient->getProject(2);
        $this->assertNotNull($project);
        $this->assertEquals("HubKick", $project->getData()->getTitle());
        $this->assertEquals("Send players to lobby on kick. /lobby / hub", $project->getData()->getTag());
        $this->assertEquals(1364368470, $project->getData()->getFirstRelease());
    }

    /**
     * @throws ApiException
     */
    public function testGetVersionsFromProject(): void
    {
        $project = $this->apiClient->getProject(2);

        $versions = $project->getVersions();
        $this->assertNotNull($versions);
        $this->assertNotEmpty($versions);
        foreach ($versions as $version) {
            $this->assertNotNull($version);
            $this->assertInstanceOf(Version::class, $version);
            $this->assertEquals($project, $version->getProject());
        }
    }

    /**
     * @throws ApiException
     */
    public function testGetVersion(): void
    {
        // PlaceholderAPI by HelpChat
        $version = $this->apiClient->getVersion(352711);
        $this->assertNotNull($version);
        $this->assertEquals("2.10.9 back to normal", $version->getData()->getTitle());
        $this->assertEquals(352711, $version->getData()->getId());
        $this->assertEquals(6245, $version->getData()->getResourceId());
    }

    /**
     * @throws ApiException
     */
    public function testGetAuthor(): void
    {
        $author = $this->apiClient->getAuthor(100356);
        $this->assertNotNull($author);
        $this->assertNotNull($author->getData());
        $this->assertEquals("Luck", $author->getData()->getUsername());
    }

    /**
     * @throws ApiException
     */
    public function testFindAuthor(): void
    {
        $author = $this->apiClient->findAuthor("Luck");
        $this->assertNotNull($author);
        $this->assertNotNull($author->getData());
        $this->assertEquals("Luck", $author->getData()->getUsername());
        $this->assertEquals(100356, $author->getData()->getId());
    }

}