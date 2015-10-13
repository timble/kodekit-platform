<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Pages Template Helper
 *
 * @author   Tom Janssens <http://github.com/tomjanssens>
 * @package  Nooku\Component\Pages
 */
class TemplateHelperMenu extends Library\TemplateHelperAbstract
{
    public function render($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'pages'   => array(),
            'active'  => null,
            'attribs' => array('class' => array('nav'))
        ));

        $result     = '';
        $first      = true;
        $last_level = 0;

        $pages = clone $config->pages;

        // We use a CachingIterator to peek ahead to the next item so that we can properly close elements
        $iterator = new \CachingIterator($pages->getIterator(), \CachingIterator::TOSTRING_USE_KEY);

        foreach($iterator as $page)
        {
            $next_page = null;
            if ($iterator->hasNext()) {
                $next_page = $iterator->getInnerIterator()->current();
            }

            $next_level = is_object($next_page) ? count(explode('/', $next_page->path)) : false;
            $level = count(explode('/', $page->path));

            // Start a new level
            if($level > $last_level)
            {
                $attributes = $first ? ' '.$this->buildAttributes($config->attribs) : '';
                $result .= "<ul$attributes>";

                // Used to put the title in the menu
                if($first && $config->title) {
                    $result .= '<li class="nav-header">'.$config->title."</li>";
                }

                $first = false;
            }

            $classes = array();
            if($config->active)
            {
                if(in_array($page->id, array_merge($config->active->getParentIds(), (array) $config->active->id))) {
                    $classes[] = 'active';
                }

                if($page->id == $config->active->id) {
                    $classes[] = 'current';
                }

                foreach($config->pages as $value)
                {
                    if(strpos($value->path, $page->path.'/') === 0)
                    {
                        $classes[] = 'parent';
                        break;
                    }
                }
            }

            $result .= '<li'.($classes ? ' class="'.implode(' ', $classes).'"' : '').">";

            if($page->link_url)
            {
                $link = $this->getTemplate()->route($page->getLink()->getQuery());
                $result .= '<a href="'.(string) $link.'">';
                $result .= $page->title;
                $result .= '</a>';
            }
            else
            {
                $result .= '<span class="separator '.($config->disabled ? 'nolink' : '').'">';
                $result .= $page->title;
                $result .= '</span>';
            }

            //$result .= $level;
            if ($level < $next_level) {
                // don't close <li>
            }
            elseif ($level === $next_level) {
                $result .= "</li>";
            }
            elseif ($next_level === false || $level > $next_level)
            {
                // Last one of the level
                $result .= "</li>";

                for($i = 0; $i < $level - $next_level; ++$i)
                {
                    if($next_level === false) {
                        $result .= "</ul>";
                    } else {
                        $result .= "</ul></li>";
                    }
                }
            }

            $last_level = $level;
        }

        return $result;
    }
}