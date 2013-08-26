<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Expire Template Filter
 *
 * Filter that enables full HTTP cache support by rewriting asset urls so they're unique, and update them when
 * the file is modified.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateFilterExpire extends TemplateFilterAbstract implements TemplateFilterRenderer
{
    /**
     * Quick lookup cache, mostly useful for <img /> and url() rewrites as there are often duplicates on page
     *
     * @var array
     */
    protected $_cache = array();

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => TemplateFilter::PRIORITY_LOWEST,
        ));

        parent::_initialize($config);
    }

    /**
     * Filter the template output
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function render(&$text)
    {
        // Stylesheets, favicons etc
        $text = preg_replace_callback('#<link.*href="([^"]+)".*\/>#iU', array($this, '_replace'), $text);

        // Scripts
        $text = preg_replace_callback('#<script.*src="([^"]+)".*>.*<\/script>#iU', array($this, '_replace'), $text);

        // Image tags
        $text = preg_replace_callback('#<img.*src="([^"]+)".*\/>#iU', array($this, '_replace'), $text);

        // Inline CSS URIs in attributes
        $text = preg_replace_callback('#style=".*url\(([^"]+)\)"#iU', array($this, '_replace'), $text);

        // Inline CSS URIs within style tags
        $text = preg_replace_callback('#<style.*>(.*)<\/style>#siU', array($this, '_replaceInlineCSS'), $text);
    }

    /**
     * Adds 'modified' query variable to resource URI when possible, makes browsers caching useful and failsafe
     *
     * @param string $url
     * @return string
     */
    protected function _processResourceURL($url)
    {
        if(!isset($this->_cache[$url]))
        {
            // Remote resources cannot be processed
            if($this->getObject('lib:filter.url')->validate($url)) {
                return $this->_cache[$url] = $url;
            }

            //Strip the base path from the url
            $count = 1;
            $path  = str_replace($this->getObject('request')->getBaseUrl()->getPath(), '', $url, $count);

            //Create the fully qualified file path
            $file  = $this->getObject('request')->getBasePath(true).$path;

            if(file_exists($file) && $modified = filemtime($file))
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
            $match = $this->getObject('request')->getBaseUrl()->getPath().ltrim($match, '.');
        }

        return str_replace($matches[1], $this->_processResourceURL($match), $matches[0]);
    }
}
