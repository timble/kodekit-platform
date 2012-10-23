<?php
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Page Controller Class
 *   
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
 
class ComApplicationControllerPage extends KControllerResource
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'view' => 'page'
        ));

        parent::_initialize($config);
    }

    protected function _actionGet(KCommandContext $context)
    {
        $this->getView()->content = $context->response->getContent();
        return parent::_actionGet($context);
    }
}