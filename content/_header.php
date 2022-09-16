<?php namespace lmdcode\lmdpages;
/**
 * Page Header - this is a required file
 * 
 * @var Markup $lmdpages
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$lmdpages::pageTitle(true)?></title>
    <meta name="robots" content="<?=$lmdpages::metaRobots()?>">
	<meta name="description" content="<?=$lmdpages::metaDescription()?>">
	<meta name="author" content="<?=$lmdpages::metaAuthor()?>">
	<link rel="canonical" href="<?=$lmdpages::canonical()?>">
    <?=$lmdpages::scripts('head')?>
    <?=$lmdpages::styles()?>
</head>
<body>

<header>
    <div id="header-title"><?=$lmdpages::siteTitle()?></div>
    <div id="header-text">
        <p>A demo site for LMD Pages</p>
        <p>For rapid and simple website builds.</p>
    </div>
</header>

<nav id="main-nav">
    <?=$lmdpages::navigationMenu(['ul' => ['id' => 'nav-menu']])?>
</nav>

<main>
