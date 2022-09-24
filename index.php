<?php

/**
 * LMD Pages Bootstrap/Index
 *
 * LMD Pages
 * (c) LMD, 2022
 * https://github.com/lmd-code/lmdpages
 *
 * @version 0.2.1
 */

declare(strict_types=1);

namespace lmdcode\lmdpages;

// Get root filepath
$root_path = rtrim(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__), '/');

// Includes
require $root_path . "/src/Config.php";
require $root_path . "/src/Markup.php";

/** @var Config $config */
$config = new Config($root_path); // Initialise site config

/** @var Markup $lmdpages  */
$lmdpages = new Markup($config); // Initialise site markup

// Check route for special actions
switch ($config::getRoute()) {
    case 'error404':
        $config::error404();
        break;

    default:
        break;
}

// Render content
require $root_path . "/content/_header.php";
require $root_path . "/content/" . $config::getRoute() . '.php';
require $root_path . "/content/_footer.php";
