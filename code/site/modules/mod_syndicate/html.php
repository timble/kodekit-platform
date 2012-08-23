<?php
/**
 * @version     $Id: html.php 1481 2012-02-10 01:46:24Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Default
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Syndicate Module Html View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Modules
 * @subpackage  Default
 */
 
class ModSyndicateHtml extends ModDefaultHtml
{
    public function display()
    {
        $this->module->params->set('text', 'Feed Entries');

        if($link = $this->getLink($this->module->params))
        {
            $this->assign('link'  , $link);

            return parent::display();
        }
    }

    public function getLink($params)
    {
        //@TODO : This is no longer working. Implement differently
        $document = JFactory::getDocument();

        foreach($document->_links as $link)
        {
            if(strpos($link, 'application/rss+xml'))
            {
                preg_match("#href=\"(.*?)\"#s", $link, $matches);
                return $matches[1];
            }
        }

    }
} 