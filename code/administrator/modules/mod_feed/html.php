<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Feed Module Html View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */
 
class ModFeedHtml extends ModDefaultHtml
{
    public function display()
    { 
        $this->feed = JFactory::getFeedParser($this->module->params->get('rssurl'), $this->module->params->get('cache_time', 15) * 60);	
        return parent::display();
    }
} 