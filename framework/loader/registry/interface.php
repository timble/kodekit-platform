<?php
/**
 * @package		Koowa_Loader
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

/**
 * Loader Registry Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Loader
 */
interface LoaderRegistryInterface
{
    /**
     * Enable class caching
     *
     * @param  boolean	Enable or disable the cache. Default is TRUE.
     * @return boolean	TRUE if caching is enabled. FALSE otherwise.
     */
	public function enableCache($enabled = true);

	/**
     * Set the cache prefix
     *
     * @param string The cache prefix
     * @return void
     */
	public function setCachePrefix($prefix);

	/**
     * Get the cache prefix
     *
     * @return string	The cache prefix
     */
	public function getCachePrefix();
}