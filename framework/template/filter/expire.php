<?php
/**
 * @package      Koowa_Template
 * @subpackage    Filter
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Expire Template Filter
 *
 * Filter that enables full HTTP cache support by rewriting asset urls so they're unique, and update them when
 * the file is modified.
 *
 * @author      Stian Didriksen <stian@timble.net>
 * @package     Koowa_Template
 * @subpackage  Filter
 */
class TemplateFilterExpire extends TemplateFilterAbstract implements TemplateFilterWrite
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
     * @param   object  An optional Config object with configuration options
     * @return void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'priority' => Command::PRIORITY_LOWEST,
        ));

        parent::_initialize($config);
    }

    /**
     * Filter the template output
     *
     * @param string
     * @return TemplateFilterForm
     */
    public function write(&$text)
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
        return $this;
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
            if($this->getService('lib://nooku/filter.url')->validate($url)) {
                return $this->_cache[$url] = $url;
            }

            /**
             * The count is a referenced value, so need to be passed as a variable.
             * And the count is needed to prevent the root to be replaced multiple times in a longer path.
             */
            $count = 1;
            $src   = JPATH_ROOT.str_replace(Request::root(), '', $url, $count);

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
            $match = Request::root().ltrim($match, '.');
        }

        return str_replace($matches[1], $this->_processResourceURL($match), $matches[0]);
    }
}
