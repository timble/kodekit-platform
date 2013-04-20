<?php
/**
 * @package		Koowa_Class
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Class Registry Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Class
 */
interface ClassRegistryInterface
{
    /**
     * Enable class caching
     *
     * @param  boolean	$enable Enable or disable the cache. Default is TRUE.
     * @return boolean	TRUE if caching is enabled. FALSE otherwise.
     */
	public function enableCache($enable = true);

	/**
     * Set the cache prefix
     *
     * @param string $prefix The cache prefix
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