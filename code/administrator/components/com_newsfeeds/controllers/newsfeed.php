<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Newsfeed Controller Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */
class ComNewsfeedsControllerNewsfeed extends ComDefaultControllerDefault
{ 
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'behaviors' => array('com://admin/activities.controller.behavior.loggable'),
        ));
    
        parent::_initialize($config);
    }
}