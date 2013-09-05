<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * List Template Helper
 *
 * @author   Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
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
        $last_depth = 0;

        foreach(clone $config->pages as $page)
        {
            $depth = substr_count($page->path, '/');

            if(substr($page->path, -1) != '/') {
                $depth++;
            }

            if($depth > $last_depth)
            {
                $result .= $first ? '<ul '.$this->buildAttributes($config->attribs).'>' : '<ul>';

                if($first && $config->title) {
                    $result .= '<li class="nav-header">'.$config->title.'</li>';
                }

                $last_depth = $depth;
                $first      = false;
            }

            if($depth < $last_depth)
            {
                $result .= str_repeat('</li></ul>', $last_depth - $depth);
                $last_depth = $depth;
            }

            if($depth == $last_depth) {
                $result .= '</li>';
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

            $result .= '<li '.($classes ? 'class="'.implode(' ', $classes).'"' : '').'>';
            switch($page->type)
            {
                case 'component':
                    $link = $this->getTemplate()->getView()->getRoute($page->getLink()->getQuery());
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
        }

        return $result;
    }
}