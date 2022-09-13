<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$lmdpages::pageTitle()?></title>
    <meta name="robots" content="<?=$lmdpages::metaRobots()?>">
	<meta name="description" content="<?=$lmdpages::metaDescription()?>">
	<meta name="author" content="<?=$lmdpages::metaAuthor()?>">
	<link rel="canonical" href="<?=$lmdpages::canonical()?>">
    <?=$lmdpages::scripts('head')?>
    <?=$lmdpages::styles()?>
</head>
<body x-data="navbar" @resize.window.debounce.50="$dispatch('resized')" @resized.window="resize">
<div class="skip-links">
    <a href="#content" @click.prevent="goto('content')">Skip to content</a> | 
    <a href="#mainNav" @click.prevent="goto('mainNav')">Skip to menu</a>
</div>
<div id="header-wrapper" style="background-image: url(<?=$lmdpages::imgDir()?>/header-bg.jpg)">
    <header>
        <div class="header-title">
            <?=$lmdpages::siteTitle()?>
        </div>
        <div class="header-text">
            <p>A demo site for LMD Pages</p>
            <p>For rapid and simple website builds.</p>
        </div>
    </header>
    <nav id="mainNav" tabindex="-1" :class="{'menu-js': true}">
        <button id="menuBtn" @click="menuOpen=!menuOpen" :hidden="screenWidth >= maxWidth" :aria-expanded="menuOpen" type="button" aria-controls="menu" hidden>
            <span aria-label="Toggle menu">Menu</span>
        </button>
        <ul id="menu" :hidden="!menuOpen"><?=$lmdpages::navigationMenu()?></ul>
    </nav>
</div>

<main id="content" tabindex="-1">
  