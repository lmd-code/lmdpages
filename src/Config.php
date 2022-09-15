<?php

/**
 * LMD Pages 
 * (c) LMD, 2022
 * https://github.com/lmd-code/lmdpages
 */

declare(strict_types=1);

namespace lmdcode\lmdpages;

/**
 * Site Configs and Settings
 */
class Config
{
	/**
	 * Site Data
	 * @var array<string, mixed>
	 */
	private static $data;

	/**
	 * Current route
	 * @var string
	 */
	private static $route;

	/**
	 * Current page
	 * @var array<string, mixed>
	 */
	private static $page;

	/**
	 * Absolute path to root folder
	 * @var string
	 */
	private static $rootPath;

	/**
	 * Subdirectory directory if not in root
	 * @var string
	 */
	private static $rootDir;

	/**
	 * Folders relative to root
	 * @var array
	 */
	private static $dirs = [
		'assets' => '/assets',
		'images' => '/assets/images',
		'scripts' => '/assets/scripts',
		'styles' => '/assets/styles',
		'content' => '/content',
		'blocks' => '/content/blocks',
	];

	/**
	 * Error page meta data
	 * @var array<string, string>
	 */
	private static $errorPage = [
		"navLabel" => "Error404",
		"pageTitle" => "Page Not Found (Error 404)",
		"metaDesc" => "Page not found",
		"metaRobots" => "noarchive,nosnippet,noindex",
	];

	/**
	 * Default site data JSON file
	 * @var string
	 */
	private static $defaultJson = 'site-data.json';

	/**
	 * Constructor
	 *
	 * @param string $rootPath Absolute path to root folder
	 * @param string $rootDir Subdirectory if not in root
	 * @param string $dataSrc Relative (to root) path to site data file (if not default)
	 */
	public function __construct(string $rootPath, string $rootDir = '', string $dataSrc = '')
	{
		self::$rootPath = $rootPath;
		self::$rootDir = trim($rootDir, '/');
		$dataSrc = ($dataSrc !== '') ? $dataSrc : self::$dirs['content'] . '/' . self::$defaultJson;
		self::$data = self::getSiteData($dataSrc);
		self::$route = self::getRoute();
	}

    /**
     * Get HTTP request route
     *
     * @return string
     */
    public static function getRoute(): string
    {
        if (is_null(self::$route)) {
            $route = trim(parse_url(rawurldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH), '/');

			// If in subdirectory
			if (self::$rootDir !== '') {
				$route  = trim(str_replace(self::$rootDir, '', $route), '/');
			}

            // If $route is empty use root/home page, otherwise trim '.html' file ext.
            $route = ($route === '') ? "index" : preg_replace('/\.(html|php)$/', '', $route);

			// If it isn't a valid page or the file doesn't exist, set to error404
            if (
				!array_key_exists($route, self::$data['pages']) ||
				!file_exists(self::$rootPath . self::$dirs['content'] . '/' . $route . '.php')
			) {
                $route = 'error404';
			}

            self::$route = $route;
        }

        return self::$route;
    }

	/** 
	 * Get absolute file path to root
	 * 
	 * @return string
	 */
	public static function getRootPath(): string
	{
		return self::$rootPath;
	}

	/**
	 * Get directory path
	 *
	 * @param string $name Directory name (returns root dir if empty).
	 * @param bool $fullPath Get full (absolute) file path
	 * 
	 * @return string
	 */
	public static function getDir(string $name = '', bool $fullPath = false): string
	{
		if ($fullPath) {
			$path = rtrim(self::$rootPath, '/') . '/';
		} else {
			$path = '/' . (self::$rootDir !== '' ? self::$rootDir . '/' : '');
		}

		if ($name === '') return $path;

		if (array_key_exists($name, self::$dirs)) {
			return $path . ltrim(self::$dirs[$name], '/');
		}

		return '';
	}

	/**
	 * Get site data by key
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
    public static function get(string $key)
    {
        if (!is_null(self::$data) && array_key_exists($key, self::$data)) {
            return self::$data[$key];
        }
        return null;
    }

	/**
	 * Get current page data from route
	 *
	 * @param string $key
	 * 
	 * @return mixed
	 */
	public static function getPage(string $key = '')
	{
		if (is_null(self::$page)) {
			if (!self::$route || self::$route === 'error404' || empty(self::$data['pages'])) {
				if ($key === '') return self::$errorPage;
				if (array_key_exists($key, self::$errorPage)) return self::$errorPage[$key];
				return null;
			}

			self::$page = self::$data['pages'][self::$route]; // we already know the route exists
		}

		if ($key === '') return self::$page;
		if (array_key_exists($key, self::$page)) return self::$page[$key];
		return null;
	}

	/**
	 * Error 404 Response
	 *
	 * @return void
	 */
	public static function error404(): void
	{
		// Change HTTP response code
		http_response_code(404);

		// If there is no custom template file, echo default markup and exit
		if (!file_exists(self::$rootPath . self::$dirs['content'] . '/error404.php')) {
			echo '<html><head><title>' . self::$errorPage['pageTitle'] . '</title>'
			. '<meta name="robots" content="'. self::$errorPage['metaRobots'] .'">'
			. '</head><body><h1>' . self::$errorPage['pageTitle'] . '</h1>'
			. '<p>The page you were looking could not be found.</p></body></html>';
			die();
		}
	}

	/**
	 * Get site data from JSON file
	 *
	 * @param string $dataSrc
	 *
	 * @return array
	 */
	private static function getSiteData(string $dataSrc): array
	{
		$jsonContent = file_get_contents(self::$rootPath . $dataSrc);
		if ($arr = json_decode($jsonContent, true)) {
			return $arr;
		}

		return [];
	}
}
