<?php

namespace Aternos\SpigotApi\Client;

enum Category: int
{

    /**
     * Spigot plugins that interact with BungeeCord.
     */
    case BUNGEE_SPIGOT = 2;

    case BUNGEE_SPIGOT_TRANSPORTATION = 5;

    case BUNGEE_SPIGOT_CHAT = 6;

    case BUNGEE_SPIGOT_TOOLS_AND_UTILITIES = 7;

    case BUNGEE_SPIGOT_MISC = 8;

    /**
     * Bungee plugins that interact directly with the proxy plugins folder.
     */
    case BUNGEE_PROXY = 3;

    case BUNGEE_PROXY_LIBRARIES_AND_APIS = 9;

    case BUNGEE_PROXY_TRANSPORTATION = 10;

    case BUNGEE_PROXY_CHAT = 11;

    case BUNGEE_PROXY_TOOLS_AND_UTILITIES = 12;

    case BUNGEE_PROXY_MISC = 13;

    /**
     * Plugins which work on a standard Spigot install.
     */
    case SPIGOT = 4;

    case SPIGOT_CHAT = 14;

    case SPIGOT_TOOLS_AND_UTILITIES = 15;

    case SPIGOT_MISC = 16;

    case SPIGOT_FUN = 17;

    case SPIGOT_WORLD_MANAGEMENT = 18;

    case SPIGOT_MECHANICS = 22;

    case SPIGOT_ECONOMY = 23;

    case SPIGOT_GAME_MODE = 24;

    /**
     * Skript scripts
     */
    case SPIGOT_SKRIPT = 25;

    case SPIGOT_LIBRARIES_AND_APIS = 26;

    /**
     * There are probably no resources in this category.
     */
    case SPIGOT_NO_RATING = 28;

    /**
     * Standalone applications (not websites) which are not tied to a plugin API.
     */
    case STANDALONE = 19;

    /**
     * Premium, paid addons go here.
     */
    case PREMIUM = 20;

    /**
     * Plugins which operate both on BungeeCord and Spigot.
     */
    case UNIVERSAL = 21;

    /**
     * Web resources are standalone HTML, CSS, JavaScript or PHP resources relating websites involving Minecraft.
     */
    case WEB = 27;

    /**
     * Data packs only! Texture packs, world/mini-game packs, etc not allowed.
     * Must work on Spigot.
     */
    case TRIAL_DATA_PACK = 29;
}