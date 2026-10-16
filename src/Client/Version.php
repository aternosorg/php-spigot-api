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
     * Get the project this version belongs to.
     *
     * @return Project
     * @throws ApiException
     */
    public function getProject(): Project
    {
        return $this->client->getProject($this->getProjectId());
    }
}
