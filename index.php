<?php
/**
 * LMD Pages Bootstrap/Index
 * 
 * LMD Pages 
 * (c) LMD, 2022
 * https://github.com/lmd-code/lmdpages
 * 
 * @version 0.1.0
 */

declare(strict_types=1);

namespace lmdcode\lmdpages;

// Define global root path constant
define('ROOT_PATH', rtrim(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__), '/'));

// Includes
require ROOT_PATH . "/src/Config.php"; 
require ROOT_PATH . "/src/Markup.php";

/** @var Config $config */
$config = new Config(ROOT_PATH); // Initialise site config

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
require ROOT_PATH . "/content/_header.php";
require ROOT_PATH . "/content/" . $config::getRoute() . '.php';
require ROOT_PATH . "/content/_footer.php";
