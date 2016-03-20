<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
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