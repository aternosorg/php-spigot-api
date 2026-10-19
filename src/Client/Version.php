<?php

namespace Aternos\SpigotApi\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Model\ResourceUpdate;

class Version extends ResourceUpdate
{

    public function __construct(
        protected SpigotAPIClient $client,
        ResourceUpdate            $data
    )
    {
        parent::__construct($data->container);
    }

    /**
     * @deprecated The version extends the generated model now, so its getters can be used directly.
     * @return ResourceUpdate
     */
    public function getData(): ResourceUpdate
    {
        return $this;
    }

    /**
     * Get the ID of the version.
     *
     * @return int
     */
    public function getId(): int
    {
        return intval(parent::getId());
    }

    /**
     * Get the ID of the project this version belongs to.
     *
     * @return int
     */
    public function getProjectId(): int
    {
        return intval($this->getResourceId());
    }

    /**
     * Fetch the project this version belongs to from the API.
     *
     * This sends a request to the SpigotMC API every time it is called. The result is not cached.
     *
     * @return Project
     * @throws ApiException
     */
    public function fetchProject(): Project
    {
        return $this->client->getProject($this->getProjectId());
    }

    /**
     * Fetch the project this version belongs to from the API.
     *
     * This sends a request to the SpigotMC API every time it is called. The result is not cached.
     *
     * @deprecated Use {@see fetchProject()} instead. Methods that send a request to the API are prefixed with "fetch".
     * @return Project
     * @throws ApiException
     */
    public function getProject(): Project
    {
        return $this->fetchProject();
    }
}
