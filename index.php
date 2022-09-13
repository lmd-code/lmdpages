<?php
/**
 * Main Index/Bootstrap File
 * LMD-Code (c) 2022
 */

declare(strict_types=1);

namespace lmdcode\lmdpages;

// Define global root path constant
define('ROOT_PATH', rtrim(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__), '/'));

// Includes
require ROOT_PATH . "/src/Config.php"; 
require ROOT_PATH . "/src/Markup.php";

// Initialise site config
$config = new Config(ROOT_PATH);

// Initialise site markup
$lmdpages = new Markup($config);

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
