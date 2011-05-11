<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Login Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerLogin extends ComDefaultControllerResource
{
    protected function _actionGet(KCommandContext $context)
    {
        //Force the application template
        KRequest::set('get.tmpl', 'login');
         
        //Set the status
        $context->status = KHttpResponse::UNAUTHORIZED;
           
        //Set the authentciation header
        //$context->headers = array('WWW-Authenticate', 'Basic Realm="'.KRequest::base().'"');

        return parent::_actionGet($context);
    }
}