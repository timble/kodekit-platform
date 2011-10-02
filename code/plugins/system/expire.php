<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Plugins
 * @subpackage  System
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * System plugin that enables full HTTP cache support by rewriting asset urls so 
 * they're unique, and update them when the file is modified.
 *
 * @author		Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Plugins
 * @subpackage  System
 */
class plgSystemExpire extends JPlugin
{
    /**
     * Quick lookup cache, mostly useful for <img /> and url() rewrites as there 
     * are often duplicates on page
     *
     * @var array
     */
    protected $_cache = array();
    
    /**
	 * On after render event handler
	 * 
	 * @return void
	 */
    public function onAfterRender()
    {
        $response = JResponse::getBody();
        
        // Stylesheets, favicons etc
        $response = preg_replace_callback('#<link.*href="([^"]+)".*\/>#iU', array($this, '_replace'), $response);
        
        // Scripts
        $response = preg_replace_callback('#<script.*src="([^"]+)".*>.*<\/script>#iU', array($this, '_replace'), $response);
        
        // Image tags
        $response = preg_replace_callback('#<img.*src="([^"]+)".*\/>#iU', array($this, '_replace'), $response);
        
        // Inline CSS URIs in attributes
        $response = preg_replace_callback('#style=".*url\(([^"]+)\)"#iU', array($this, '_replace'), $response);
        
        // Inline CSS URIs within style tags
        $response = preg_replace_callback('#<style.*>(.*)<\/style>#siU', array($this, '_replaceInlineCSS'), $response);
        
        JResponse::setBody($response);
    }
    
 	/**
	 * Adds 'modified' query variable to resource URI when possible, makes browsers caching useful and failsafe
	 *
	 * @return string
	 */
	protected function _processResourceURL($url)
	{
	    if(!isset($this->_cache[$url]))
	    {
    	    // Remote resources cannot be processed
    	    if(KService::get('koowa:filter.url')->validate($url)) {
    	        return $this->_cache[$url] = $url;
    	    }
    	    
    	    /** 
    	     * The count is a referenced value, so need to be passed as a variable.
    	     * And the count is needed to prevent the root to be replaced multiple times in a longer path.
    	     */
    	    $count = 1;
            $src   = JPATH_ROOT.str_replace(KRequest::root(), '', $url, $count);
            
            if(file_exists($src) && $modified = filemtime($src))
            {
                $join  = strpos($url, '?') ? '&' : '?';
                $this->_cache[$url] = $url.$join.$modified;
            } 
            else $this->_cache[$url] = $url;
        }

        return $this->_cache[$url];
	}
    
    protected function _replace($matches)
    {
        return str_replace($matches[1], $this->_processResourceURL($matches[1]), $matches[0]);
    }
    
    protected function _replaceInlineCSS($matches)
    {
        return preg_replace_callback('#url\(([^"]+)\)#iU', array($this, '_replaceInlineCSSMatch'), $matches[0]);
    }
    
    protected function _replaceInlineCSSMatch($matches)
    {
        $match = trim($matches[1], '"\'');
        if(strpos($match, '..') === 0) {
            $match = KRequest::root().ltrim($match, '.');
        }
        
        return str_replace($matches[1], $this->_processResourceURL($match), $matches[0]);
    }
}
