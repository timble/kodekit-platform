<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Password Controller Class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersControllerPassword extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config) {
        parent::__construct($config);

        $this->registerCallback('after.save', array($this, 'redirect'));
    }

    protected function _initialize(KConfig $config) {
        $config->append(array(
            'behaviors' => array(
                'com://site/users.controller.behavior.password.executable',
                'com://site/users.controller.behavior.password.resettable')));
        parent::_initialize($config);
    }

    public function redirect(KCommandContext $context) {
        $password = $context->result;

        if ($password->getStatus() == KDatabase::STATUS_FAILED) {
            $this->setRedirect(KRequest::referrer(), $password->getStatusMessage(), 'error');
        } else {
            $this->setRedirect($this->getService('application.pages')->getHome()->url, 'PASSWORD_SUCCESSFULLY_SAVED');
        }
    }
}