<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Menu Template Helper
 *
 * @author   Tom Janssens <http://github.com/tomjanssens>
 * @package  Kodekit\Component\Pages
 */
class TemplateHelperMenu extends Library\TemplateHelperAbstract
{
    public function render($config = array(), Library\TemplateInterface $template)
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'pages'       => null,
            'title'       => null,
            'attribs'     => array('class' => array('nav')),
            'max_level'   => 9,
            'active_only' => false,
        ));

        $html    = '';
        $level   = 0;
        $active  = $this->getObject('pages')->getActive();

        if($config->active_only) {
            $iterator = $config->pages->getRecursiveIterator($config->max_level, array_shift($active->getPath()));
        } else {
            $iterator = $config->pages->getRecursiveIterator($config->max_level);
        }

        foreach($iterator as $page)
        {
            if($page->canAccess())
            {
                if($iterator->getLevel() > $level)
                {
                    $attributes = $iterator->getLevel() == 1 ? ' '.$this->buildAttributes($config->attribs) : '';
                    $html .= "<ul$attributes>";

                    // Add the title to the menu
                    if($iterator->getLevel() == 1 && $config->title) {
                        $html .= '<li class="nav-header">'.$config->title."</li>";
                    }
                }

                $class = array();
                if($active)
                {
                    if(in_array($page->id, $active->getPath())) {
                        $class[] = 'active';
                    }

                    if($page->id == $active->id) {
                        $class[] = 'current';
                    }

                    if($page->hasChildren()) {
                        $class[] = 'parent';
                    }
                }

                $html .= '<li'.($class ? ' class="'.implode(' ', $class).'"' : '').">";

                if($link = $page->getLink())
                {
                    $link = $template->route($link->getQuery());
                    $html .= '<a href="'.(string) $link.'">';
                    $html .= $page->title;
                    $html .= '</a>';
                }
                else
                {
                    $html .= '<span class="separator '.($config->disabled ? 'nolink' : '').'">';
                    $html .= $page->title;
                    $html .= '</span>';
                }


                if(!$page->hasChildren())
                {
                    $html .= "</li>";

                    if(!$iterator->hasNext()) {
                        $html .= "</ul>";
                    }
                }

                $level = $iterator->getLevel();
            }
        }

        return $html;
    }
}