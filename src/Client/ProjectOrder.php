<?php

namespace Aternos\SpigotApi\Client;

/**
 * Fields that projects can be ordered by.
 */
enum ProjectOrder: string
{
    case ID = "id";

    case FIRST_RELEASE = "first_release";

    case LAST_UPDATE = "last_update";

    case DOWNLOAD_COUNT = "download_count";
}
