<?php

namespace Aternos\SpigotApi\Client;

/**
 * Fields that versions can be ordered by.
 */
enum VersionOrder: string
{
    case ID = "id";

    case POST_DATE = "post_date";

    case DOWNLOAD_COUNT = "download_count";
}
