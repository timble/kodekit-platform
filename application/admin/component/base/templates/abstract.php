<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Abstract Template
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
abstract class ComBaseTemplateAbstract extends Framework\TemplateAbstract
{
	/**
	 * The cache object
	 *
	 * @var	JCache
	 */
    protected $_cache;

	/**
	 * Constructor
	 *
	 * Prevent creating instances of this class by making the constructor private
	 *
	 * @param 	object 	An optional Framework\Config object with configuration options
	 */
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);

		if(JFactory::getConfig()->getValue('config.caching')) {
	        $this->_cache = JFactory::getCache('template', 'output');
		}
	}

	/**
	 * Searches for the file
	 *
	 * This function first tries to find a template override, if no override exists it will try to find the default
     * template
	 *
	 * @param	string	The file path to look for.
	 * @return	mixed	The full path and file name for the target file, or FALSE
	 * 					if the file is not found
	 */
	public function findFile($file)
	{
        //Theme override
        $theme  = $this->getService('application')->getTheme();
        $theme  = JPATH_APPLICATION.'/public/theme/'.$theme.'/templates';
	    $theme .= str_replace(array(JPATH_ROOT.'/component', '/views', '/templates'), '', $file);

        //Application override
        $application = str_replace(JPATH_ROOT, JPATH_APPLICATION, $file);

        //Try to find the template
        foreach(array($theme, $application, $file) as $path)
        {
            $result = parent::findFile($path);
            if($result !== false) {
                break;
            }
        }

	    return $result;
	}

    /**
     * Parse the template
     *
     * This function implements a caching mechanism when reading the template. If the template cannot be found in the
     * cache it will be filtered and stored in the cache. Otherwise it will be loaded from the cache and returned
     * directly.
     *
     * @param string The template content to parse
     * @return void
     */
    protected function _parse(&$content)
    {
        if(isset($this->_cache))
        {
            $identifier = md5($this->getPath());

            if (!$this->_cache->get($identifier))
            {
                parent::_parse($content);

                //Store the object in the cache
                $this->_cache->store($content, $identifier);
            }
            else $content = $this->_cache->get($identifier);
        }
        else parent::_parse($content);
    }
}