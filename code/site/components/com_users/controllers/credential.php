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
 * Password controller class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersControllerCredential extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config) {

        parent::__construct($config);

        $this->registerCallback('after.edit', array($this, 'redirect'));
    }

    protected function _initialize(KConfig $config) {
        $config->append(array('behaviors' => array('com://site/users.controller.behavior.credential.executable')));
        parent::_initialize($config);
    }

    public function redirect(KCommandContext $context) {

        $result = $context->result;

        if ($result && $result->getStatus() == KDatabase::STATUS_FAILED) {
            $this->setRedirect(KRequest::referrer(), $result->getStatusMessage(), 'error');
        } elseif ($context->data->password) {
            $this->setRedirect(KRequest::referrer(), JText::_('Your password has been successfully updated.'),
                'message');
        }
    }
}