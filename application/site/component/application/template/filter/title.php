<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Title Template Filter
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Application
 */
class ApplicationTemplateFilterTitle extends Application\TemplateFilterTitle
{
    public function render(&$text)
    {
        $title = $this->_parseTags($text);

        //Get the parameters of the active menu item
        $page   = $this->getObject('application.pages')->getActive();
        $params = new JParameter($page->params);

        if($params->get('page_title')) {
            $title = $this->_renderTag(array(), $params->get('page_title'));
        }

        $text = str_replace('<ktml:title>'.PHP_EOL, $title, $text);
    }
}