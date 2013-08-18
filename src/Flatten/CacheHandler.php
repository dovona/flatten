<?php
namespace Flatten;

use Illuminate\Container\Container;

/**
 * Handles the caches of the various pages
 */
class CacheHandler
{
	/**
	 * The current cache hash
	 *
	 * @var string
	 */
	protected $hash;

	/**
	 * The IoC Container
	 *
	 * @var Container
	 */
	protected $app;

	/**
	 * Build a new CacheHandler
	 *
	 * @param Container $app
	 * @param string    $hash
	 */
	public function __construct(Container $app, $hash)
	{
		$this->app  = $app;
		$this->hash = $hash;
	}

	/**
	 * Returns the current page in cache
	 *
	 * @return string
	 */
	public function getCache()
	{
		return $this->app['cache']->get($this->hash);
	}

	/**
	 * Check if Flatten has a cache of the current page
	 *
	 * @return boolean
	 */
	public function hasCache()
	{
		return $this->app['cache']->has($this->hash);
	}

	/**
	 * Store contents in the cache
	 *
	 * @param string $content The content to store
	 *
	 * @return void
	 */
	public function storeCache($content)
	{
		if (!$content) {
			return false;
		}

		// Log caching
		if ($this->app->bound('log')) {
			$this->app['log']->info('Caching page '.$this->hash);
		}

		return $this->app['cache']->put(
			$this->hash, $content, $this->getLifetime()
		);
	}

	/**
	 * Return the configured cache lifetime
	 *
	 * @return integer
	 */
	public function getLifetime()
	{
		return (int) $this->app['config']->get('flatten::lifetime');
	}

	////////////////////////////////////////////////////////////////////
	///////////////////////////// PAGE HASH ////////////////////////////
	////////////////////////////////////////////////////////////////////

	/**
	 * Get the URL hash
	 *
	 * @return string
	 */
	public function getHash()
	{
		return $this->hash;
	}
}