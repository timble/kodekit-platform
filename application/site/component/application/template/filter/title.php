<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Title Template Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Application
 */
class ApplicationTemplateFilterTitle extends Application\TemplateFilterTitle
{
    public function render(&$text)
    {
        $title = $this->_parseTags($text);

        //Get the parameters of the active menu item
        $title = '';
        if($page = $this->getObject('application.pages')->getActive())
        {
            $params = $page->getParams('page');
            $title  = htmlspecialchars_decode($this->getObject('application')->getTitle());

            if($params->get('page_title', $title)) {
                $title = $this->_renderTag(array(), $params->get('page_title'));
            }
        }

        $text = str_replace('<ktml:title>', $title, $text);
    }
}