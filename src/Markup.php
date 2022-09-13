<?php
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
        return trim(self::$config::get('siteUrl'), '/') . '/' . self::$config::getRoute();
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
     * @param string $sep Separator between page title and site title (inc. spaces)
     *
     * @return string
     */
    public static function pageTitle(string $sep = ' - '): string
    {
        $title = "";
        if (!empty(self::$config::getPage('pageTitle'))) {
            $title = self::$config::getPage('pageTitle') . $sep;
        } 

        return $title . self::siteTitle();
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
        $scripts = [];

        $globalScripts = self::$config::get('scripts');
        $pageScripts = self::$config::getPage('scripts');

        if ($loc === 'head') {
            if (!empty($globalScripts['head'])) {
                $scripts = array_merge($scripts, $globalScripts['head']);
            }

            if (!empty($pageScripts['head'])) {
                $scripts = array_merge($scripts, $pageScripts['head']);
            }
        }

        if ($loc === 'foot') {
            if (!empty($globalScripts['foot'])) {
                $scripts = array_merge($scripts, $globalScripts['foot']);
            }

            if (!empty($pageScripts['foot'])) {
                $scripts = array_merge($scripts, $pageScripts['foot']);
            }
        }

        $out = "";
        foreach ($scripts as $script) {
            $dir = (self::$config::getDir() !== '') ? '/'. self::$config::getDir() : '';
            $out .= "\t<script "
            . ((isset($script['defer']) && $script['defer'] === true) ? 'defer ' : '')
            . "src=\"{$dir}{$script['src']}\"></script>\n";
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
        $styles = !empty(self::$config::get('styles')) ? self::$config::get('styles') : [];

        if (!empty(self::$config::getPage('styles'))) {
            $styles = array_merge($styles, self::$config::getPage('styles'));
        }

        $out = "";
        foreach ($styles as $style) {
            $dir = (self::$config::getDir() !== '') ? '/'. self::$config::getDir() : '';
            $out .= "\t<link href=\"{$dir}{$style}\" rel=\"stylesheet\">\n";
        }

        return ltrim($out);
    }

    /**
     * Render the main navigation menu
     *
     * @return string
     */
    public static function navigationMenu(): string
    {
        $currentPage = self::$config::getRoute();

        $out = '';
        foreach (self::$config::get('pages') as $key => $val) {
            $title = (isset($val['navLabelTitle'])) ? ' title="' . $val['navLabelTitle'] . '"' : '';

            $out .= "<li>";

            if ($key === $currentPage) {
                $out .= "<span{$title}>{$val['navLabel']}</span>";
            } else {
                $out .= "<a href=\"" . self::getUrl($key) . "\"{$title}>{$val['navLabel']}</a>";
            }

            $out .= "</li>";
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
        $file = self::$config::getRootPath() . '/content/blocks/' . $block . '.php';

        if (!file_exists($file)) return '';

        if (count($data) > 0) {
            extract($data);
        }

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
        $dir = (self::$config::getDir() !== '') ? '/' . self::$config::getDir() : '';
        return $dir . self::$config::get('imageDir');
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
        $route = (is_null($route) || $route === '') ? self::$config::getRoute() : $route;

        if (array_key_exists($route, self::$config::get('pages'))) {
            $dir = (self::$config::getDir() !== '') ? self::$config::getDir() . '/' : '';
            $route = ($route === 'index') ? '' : $route; // home page

            return (($full) ? self::$config::get('siteUrl') : '') . '/' . $dir . $route;
        }

        return '';
    }

    /**
     * Obfuscate an email address string
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