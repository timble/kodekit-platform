<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
class ViewWindowHtml extends Library\ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'auto_fetch'       => false,
            'template_filters' => array('block'),
        ));

        parent::_initialize($config);
    }

    public function getTitle()
    {
        $title = '';
        if($page = $this->getObject('pages')->getActive())
        {
            $params = $page->getParams('page');
            $title  = htmlspecialchars_decode($page->title);

            if($params->get('page_title')) {
                $title = $params->get('page_title');
            }
        }

        return $title;
    }
}