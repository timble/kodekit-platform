<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Captcha controller class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersControllerCaptcha extends ComDefaultControllerResource
{
    public function __construct(KConfig $config)
    {
        // TODO: Remove when the getActions problem at KControllerAbstract (http://ow.ly/a7jl3) is resolved:
        $this->getActions();

        parent::__construct($config);
    }

    protected function _initialize(KConfig $config) 
    {
        $config->append(array('behaviors' => array('com://site/users.controller.behavior.captcha.captchable')));
        
        parent::_initialize($config);
    }
}