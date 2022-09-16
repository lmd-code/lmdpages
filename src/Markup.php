<?php

/**
 * LMD Pages 
 * (c) LMD, 2022
 * https://github.com/lmd-code/lmdpages
 */

declare(strict_types=1);

namespace lmdcode\lmdpages;

/**
 * Markup Helpers
 */
class Markup
{
    /**
     * Site config
     * @var Config
     */
    private static $config;

    /**
     * Constructor
     *
     * @param Config $config Site data, configs and settings
     *
     * @return void
     */
    public function __construct(Config $config)
    {
        self::$config = $config;
    }

    /**
     * Get canonical URL
     */
    public static function canonical(): string
    {
        return self::getUrl(null, true);
    }

    /**
     * Global Site Title
     *
     * @return string
     */
    public static function siteTitle(): string
    {
        if (!empty(self::$config::get('siteTitle'))) {
            return self::$config::get('siteTitle');
        }

        return "";
    }

    /**
     * Page title
     *
     * @param boolean $incSiteTitle Include the site title after page title (for <title> tag)
     * @param string $sep Separator between page title and site title (inc. spaces)
     *
     * @return string
     */
    public static function pageTitle(bool $incSiteTitle = false, string $sep = ' - '): string
    {
        $title = "";
        if (!empty(self::$config::getPage('pageTitle'))) {
            $title = self::$config::getPage('pageTitle');
        } 

        return $title . ($incSiteTitle ? $sep . self::siteTitle() : '');
    }
    
    /**
     * Page meta description
     *
     * @return string
     */
    public static function metaDescription(): string
    {
        return self::$config::getPage('metaDesc');
    }
    
    /**
     * Page meta robots
     *
     * @return string
     */
    public static function metaRobots(): string
    {
        return self::$config::getPage('metaRobots');
    }

    /**
     * Page meta author
     *
     * @return string
     */
    public static function metaAuthor(): string
    {
        if (!empty(self::$config::getPage('metaAuthor'))) {
            return self::$config::getPage('metaRobots');
        }
        
        if (!empty(self::$config::get('metaAuthor'))) {
            return self::$config::get('metaAuthor');
        }

        return '';
    }

    /**
     * Render scripts
     *
     * @param string $loc Script insert location ('head' or 'foot').
     *
     * @return string
     */
    public static function scripts(string $loc = 'head'): string
    {
        if (!in_array($loc, ['head', 'foot'])) return ''; // not a valid location

        $scriptsDir = self::$config::getDir('scripts'); // scripts folder
        $currentPage = self::$config::getRoute(); // current page
        $scripts = self::$config::getScripts(); // get all scripts (global/page)

        $out = "";

        // Global scripts
        if (array_key_exists($loc, $scripts['global'])) {
            foreach ($scripts['global'][$loc] as $script) {
                $out .= "\t<script "
                . ((isset($script['defer']) && $script['defer'] === true) ? 'defer ' : '')
                . "src=\"{$scriptsDir}/{$script['src']}\"></script>\n";
            }
        }

        // Scripts for current page
        if (
            array_key_exists($currentPage, $scripts['page'])
            && array_key_exists($loc, $scripts['page'][$currentPage])
        ) {
            foreach ($scripts['page'][$currentPage][$loc] as $script) {
                $out .= "\t<script "
                . ((isset($script['defer']) && $script['defer'] === true) ? 'defer ' : '')
                . "src=\"{$scriptsDir}/{$script['src']}\"></script>\n";
            }
        }

        return ltrim($out);
    }

    /**
     * Render stylesheets
     *
     * @return string
     */
    public static function styles(): string
    {
        $stylesDir = self::$config::getDir('styles'); // style folder
        $currentPage = self::$config::getRoute(); // current page
        $styles = self::$config::getStyles(); // get all styles (global/page)

        $out = "";

        // Global styles
        foreach ($styles['global'] as $style) {
            $out .= "\t<link href=\"{$stylesDir}/{$style}\" rel=\"stylesheet\">\n";
        }

        // Styles for current page
        if (array_key_exists($currentPage, $styles['page'])) {
            foreach ($styles['page'][$currentPage] as $style) {
                $out .= "\t<link href=\"{$stylesDir}/{$style}\" rel=\"stylesheet\">\n";
            }
        }

        return ltrim($out);
    }

    /**
     * Render the main navigation menu as a list
     * 
     * @param array $elementAttrs Custom attributes for HTML elements
     *
     * @return string
     */
    public static function navigationMenu(array $elementAttrs = []): string
    {
        $pages = self::getMenuItems();
        if (count($pages) < 1) return '';

        // Valid HTML elements that can have custom attributes
        $attrs = [
            'ul' => '',
            'li' => '',
            'a' => '',
            'span' => '',
        ];

        $ignoreAttrs = ['title', 'href']; // do not allow these to be customised

        // Build attributes for HTML elements
        foreach ($elementAttrs  as $ele => $attributes) {
            if (!array_key_exists($ele, $attrs) || !is_array($attributes)) continue;

            foreach ($attributes as $key => $val) {
                if (!is_string($key) || !is_string($val) || in_array($key, $ignoreAttrs)) continue;

                $attrs[$ele] .= ' '.$key.'="' . htmlentities($val, ENT_COMPAT, null, false) . '"';
            }
        }

        // Build menu
        $out = "<ul{$attrs['ul']}>";
        foreach ($pages as $key => $val) {
            $title = ($val['title'] !== '') ? ' title="' . $val['title'] . '"' : '';

            $out .= "<li{$attrs['li']}>";

            if ($val['isCurrent']) {
                $out .= "<span{$attrs['span']}{$title}>{$val['text']}</span>";
            } else {
                $out .= "<a href=\"{$val['link']}\"{$attrs['a']}{$title}>{$val['text']}</a>";
            }

            $out .= "</li>";
        }
        $out .= "</ul>";

        return $out;
    }

    /**
     * Return menu items
     *
     * @return array
     */
    public static function getMenuItems(): array
    {
        $currentPage = self::$config::getRoute();

        $pages = [];

        foreach (self::$config::get('pages') as $key => $val) {
            $pages[$key] = [
                'link' => self::getUrl($key),
                'text' => $val['navLabel'],
                'title' => (isset($val['navLabelTitle']) ? $val['navLabelTitle'] : ''),
                'isCurrent' => ($currentPage === $key),
            ];
        }

        return $pages;
    }

    /**
     * Render a data source
     * 
     * Loops through a data source (JSON file) and applies the `renderBlock()`
     * method to each item.
     *
     * @param string $dataFile Path relative to the 'content' directory
     * @param string $block Block file name (without extension) to render
     *
     * @return string
     */
    public static function renderData(string $dataFile, string $block): string
    {
        $out = '';

        $dataFile = '/'. ltrim($dataFile, '/');

        $filePath = self::$config::getDir('content', true) . $dataFile;

        if (!file_exists($filePath)) return '';

        $jsonContent = file_get_contents($filePath);
		if ($data = json_decode($jsonContent, true)) {
			foreach ($data as $item) {
                $out .= self::renderBlock($block, $item);
            } 
		}

        return $out;
    }

    /**
     * Render a content block
     * 
     * @param string $block Block file name (without extension)
     * @param array $data Any data key/vals to be extracted as vars
     * 
     * @return string 
     */
    public static function renderBlock(string $block, array $data = []): string
    {
        $block = '/' . preg_replace('/\.php$/i', '', trim($block, ' /'));

        $file = self::$config::getDir('blocks', true) . $block . '.php';

        if (!file_exists($file)) return '';

        if (count($data) > 0) {
            extract($data);
        }

        $lmdpages = __CLASS__; // make $lmdpages available to blocks

        ob_start();
        require $file;
        $out = ob_get_contents();
        ob_end_clean();

        return $out;
    }

    /**
     * Get image directory
     *
     * @return string
     */
    public static function imgDir(): string
    {
        return self::$config::getDir('images');
    }

    /**
     * Get URL for a page route
     *
     * @param ?string $route Specify route, or leave blank for current page
     * @param boolean $full Include full addres (http://etc...), otherwise local url
     *
     * @return string
     */
    public static function getUrl(?string $route = null, bool $full = false): string
    {
        // Get current page route if none specified
        $route = (is_null($route) || $route === '') ? self::$config::getRoute() : $route;

        if (array_key_exists($route, self::$config::get('pages'))) {
            // Check if route is actually the home page
            $route = ($route === 'index') ? '' : $route;

            // Get full URL if requested
            $fullUrl = $full ? trim(self::$config::get('siteUrl'), '/') : '';

            return $fullUrl . self::$config::getDir() . $route;
        }

        return ''; // inalid page route
    }

    /**
     * Obfuscate an email address string
     * 
     * This method works in conjunction with the `contact.js` file in the 
     * `assets/scripts/` folder.
     *
     * @param string $email The email address to obfuscate
     * @param boolean $isLink Make the email a clickable link (default: true)
     *
     * @return string
     */
    public static function obfuscateEmail(string $email, bool $isLink = true): string
    {
        [$to, $at] = explode("@", $email);
        $safeEmail = $to . " &#91;AT&#93; " . str_replace('.', ' &#91;DOT&#93; ', $at);
        $dataEmail = str_replace('.', '|', $at);

        $out = '<span data-eod="' . $dataEmail . '" data-eoa="'.$to.'" data-eol="'
        . ($isLink ? 'true' : '') . '">' . $safeEmail . '</span>';

        return $out;
    }
}