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
 * List Template Helper
 *
 * @author   Tom Janssens <http://github.com/tomjanssens>
 * @package  Nooku\Component\Pages
 */
class TemplateHelperList extends Library\TemplateHelperAbstract
{
    public function pages($config = array())
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
        $collection = new \CachingIterator($pages->getIterator(), \CachingIterator::TOSTRING_USE_KEY);

        foreach($collection as $page)
        {
            $next_page = null;
            if ($collection->hasNext()) {
                $next_page = $collection->getInnerIterator()->current();
            }

            $next_level = is_object($next_page) ? count(explode('/', $next_page->path)) : false;
            $level = count(explode('/', $page->path));

            // Start a new level
            if($level > $last_level)
            {
                $attributes = $first ? ' '.$this->buildAttributes($config->attribs) : '';
                $result .= "<ul$attributes>";

                // Used to put the title in the menu
                if($first && $config->title)
                {
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

            if($page->type == 'separator') {
                $classes[] = 'nav-header';
            }

            $result .= '<li'.($classes ? ' class="'.implode(' ', $classes).'"' : '').">";

            switch($page->type)
            {
                case 'component':
                    $link = $this->getTemplate()->route($page->getLink()->getQuery());
                    $result .= '<a href="'.(string) $link.'">';
                    $result .= $page->title;
                    $result .= '</a>';
                    break;

                case 'menulink':
                    $page_linked = $this->getObject('application.pages')->getPage($page->getLink()->query['Itemid']);
                    $result .= '<a href="'.$page_linked->getLink().'">';
                    $result .= $page->title;
                    $result .= '</a>';
                    break;

                case 'separator':
                    $result .= '<span class="separator '.($config->disabled ? 'nolink' : '').'">'.$page->title.'</span>';
                    break;

                case 'url':
                    $result .= '<a href="'.$page->getLink().'">';
                    $result .= $page->title;
                    $result .= '</a>';
                    break;

                case 'redirect':
                    $result .= '<a href="'.$page->route.'">';
                    $result .= $page->title;
                    $result .= '</a>';
            }

            //$result .= $level;
            if ($level < $next_level) {
                // don't close <li>
            }
            elseif ($level === $next_level) {
                $result .= "</li>";
            }
            elseif ($next_level === false || $level > $next_level) {
                // Last one of the level
                $result .= "</li>";

                for($i = 0; $i < $level - $next_level; ++$i){
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