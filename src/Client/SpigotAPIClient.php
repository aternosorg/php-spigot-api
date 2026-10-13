<?php

namespace Aternos\SpigotApi\Client;

use Aternos\SpigotApi\Api\DefaultApi;
use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Client\List\PaginatedAuthorProjectList;
use Aternos\SpigotApi\Client\List\PaginatedCategoryProjectList;
use Aternos\SpigotApi\Client\List\PaginatedProjectList;
use Aternos\SpigotApi\Client\List\PaginatedVersionList;
use Aternos\SpigotApi\Configuration;
use Composer\InstalledVersions;
use OutOfBoundsException;
use Psr\Http\Client\ClientInterface;

class SpigotAPIClient
{
    /**
     * Name of this package on packagist, used to look up the installed version
     */
    public const string PACKAGE_NAME = "aternos/spigot-api";

    protected ?ClientInterface $httpClient;
    protected Configuration $configuration;
    protected DefaultApi $api;

    public function __construct(?Configuration $configuration = null, ?ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient;

        $this->setConfiguration($configuration ?? (new Configuration())
            ->setUserAgent(static::getDefaultUserAgent()));
    }

    /**
     * Get the installed version of this library, e.g. "2.4.0" or "dev-main"
     * Returns "unknown" if the version cannot be determined, e.g. if the library was not installed using composer.
     *
     * @return string
     */
    public static function getLibraryVersion(): string
    {
        try {
            return InstalledVersions::getPrettyVersion(static::PACKAGE_NAME) ?? "unknown";
        } catch (OutOfBoundsException) {
            return "unknown";
        }
    }

    /**
     * Get the user agent that identifies this library, e.g. "aternos/php-spigot-api/2.4.0"
     * It is used unless a different user agent is set with {@see setUserAgent()}.
     *
     * @return string
     */
    public static function getDefaultUserAgent(): string
    {
        return "aternos/php-spigot-api/" . static::getLibraryVersion();
    }

    /**
     * @param Configuration $configuration
     * @return $this
     */
    public function setConfiguration(Configuration $configuration): static
    {
        $this->configuration = $configuration;
        $this->configuration->setBooleanFormatForQueryString(Configuration::BOOLEAN_FORMAT_STRING);

        $this->api = new DefaultApi($this->httpClient, $this->configuration);

        return $this;
    }

    /**
     * Set the user agent used for HTTP requests.
     * This replaces the default user agent of this library, see {@see getDefaultUserAgent()}.
     *
     * @param string $userAgent user agent identifying your application, ideally including contact information
     * @return $this
     */
    public function setUserAgent(string $userAgent): static
    {
        $this->configuration->setUserAgent($userAgent);
        return $this->setConfiguration($this->configuration);
    }

    /**
     * Set the HTTP client used for all requests.
     * When null, the default HTTP client from Guzzle will be used.
     *
     * @param ClientInterface|null $httpClient
     * @return $this
     */
    public function setHttpClient(?ClientInterface $httpClient): static
    {
        $this->httpClient = $httpClient;
        return $this->setConfiguration($this->configuration);
    }

    /**
     * List projects
     *
     * @param int $page
     * @param ProjectOrder|null $order Field to order the results by. When null, the API default (id) is used.
     * @param SortDirection|null $direction Direction to order the results by. When null, the API default (asc) is used.
     * @return PaginatedProjectList
     * @throws ApiException
     */
    public function listProjects(
        int            $page = 1,
        ?ProjectOrder  $order = null,
        ?SortDirection $direction = null,
    ): PaginatedProjectList
    {
        $resources = $this->api->listResources(null, $page, $order?->value, $direction?->value);

        return new PaginatedProjectList($this, $page, $resources, $order, $direction);
    }

    /**
     * List projects for a given category
     *
     * @param Category $category
     * @param int $page
     * @param ProjectOrder|null $order Field to order the results by. When null, the API default (id) is used.
     * @param SortDirection|null $direction Direction to order the results by. When null, the API default (asc) is used.
     * @return PaginatedCategoryProjectList
     * @throws ApiException
     */
    public function listProjectsForCategory(
        Category       $category,
        int            $page = 1,
        ?ProjectOrder  $order = null,
        ?SortDirection $direction = null,
    ): PaginatedCategoryProjectList
    {
        $resources = $this->api->listResources($category->value, $page, $order?->value, $direction?->value);

        return new PaginatedCategoryProjectList($this, $category, $page, $resources, $order, $direction);
    }

    /**
     * Get a project by its ID
     *
     * @param int $id Project ID
     * @return Project
     * @throws ApiException
     */
    public function getProject(int $id): Project
    {
        return new Project($this, $this->api->getResource($id));
    }

    /**
     * Get a version by ID
     *
     * @param int $id Version ID
     * @return Version
     * @throws ApiException
     */
    public function getVersion(int $id): Version
    {
        return new Version($this, $this->api->getResourceUpdate($id));
    }

    /**
     * Get a list of versions from a project
     *
     * @param int $id The project ID
     * @param int $page
     * @param VersionOrder|null $order Field to order the results by. When null, the API default (id) is used.
     * @param SortDirection|null $direction Direction to order the results by. When null, the API default (asc) is used.
     * @return PaginatedVersionList
     * @throws ApiException
     */
    public function getProjectVersions(
        int            $id,
        int            $page = 1,
        ?VersionOrder  $order = null,
        ?SortDirection $direction = null,
    ): PaginatedVersionList
    {
        $resources = $this->api->getResourceUpdates($id, $page, $order?->value, $direction?->value);

        return new PaginatedVersionList($this, $id, $page, $resources, $order, $direction);
    }

    /**
     * Get an author by ID
     *
     * @param int $id Author ID
     * @return Author
     * @throws ApiException
     */
    public function getAuthor(int $id): Author
    {
        return new Author($this, $this->api->getAuthor($id));
    }

    /**
     * Find an author by name
     *
     * @param string $name Author name
     * @throws ApiException
     */
    public function findAuthor(string $name): Author
    {
        $author = $this->api->findAuthor($name);
        return new Author($this, $author);
    }

    /**
     * Get a list of projects by an author
     *
     * @param int $id Author ID
     * @param int $page
     * @return PaginatedAuthorProjectList
     * @throws ApiException
     */
    public function getAuthorProjects(int $id, int $page = 1): PaginatedAuthorProjectList
    {
        $resources = $this->api->getResourcesByAuthor($id, $page);

        return new PaginatedAuthorProjectList($this, $id, $page, $resources);
    }

}