<?php

namespace Aternos\SpigotApi\Client;

use Aternos\SpigotApi\ApiException;
use Aternos\SpigotApi\Model\ResourceUpdate;

class Version
{

    public function __construct(
        protected SpigotAPIClient $client,
        protected ResourceUpdate  $data
    )
    {
    }

    public function getData(): ResourceUpdate
    {
        return $this->data;
    }

    /**
     * Get the ID of the version.
     *
     * @return int
     */
    public function getId(): int
    {
        return intval($this->data->getId());
    }

    /**
     * Get the ID of the project this version belongs to.
     *
     * @return int
     */
    public function getProjectId(): int
    {
        return intval($this->getData()->getResourceId());
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