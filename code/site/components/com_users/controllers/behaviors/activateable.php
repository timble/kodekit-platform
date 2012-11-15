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
 * Activateable Controller Behavior Class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersControllerBehaviorActivateable extends KControllerBehaviorAbstract
{
    /**
     * @var mixed bool Determines whether new created items need activation or not.
     */
    protected $_enable;

    public function __construct(KConfig $config) {
        parent::__construct($config);

        $this->_enable = $config->enable;
    }

    protected function _initialize(KConfig $config) {
        $parameters = $this->getService('application.components')->users->params;
        $config->append(array('enable' => $parameters->get('useractivation', '1')));
        parent::_initialize($config);
    }

    /**
     * Handles item activation via GET requests.
     *
     * @param KCommandContext The command context.
     */
    protected function _afterControllerRead(KCommandContext $context) {
        $request = $this->getRequest();
        $item    = $context->result;
        if (($activation = $request->activation) && $item->activation) {
            $this->activate(array('activation' => $activation));
        }
    }

    protected function _beforeControllerActivate(KCommandContext $context) {
        $activation = $context->data->activation;
        $item       = $this->getModel()->getItem();

        if ($activation !== $item->activation) {
            $msg = JText::_('Wrong activation token');
            $url = KRequest::root();

            $context->response->setRedirect($url);
            //@TODO : Set message in session
            //$this->setRedirect($url, $msg);

            return false;
        }
    }

    protected function _actionActivate(KCommandContext $context) {
        $item             = $this->getModel()->getItem();
        $item->activation = '';
        $item->enabled    = 1;
        if ($item->save()) {
            $msg = JText::_('Activation successfully completed');
            $url = $this->getService('application.pages')->home->link;
            $context->response->setRedirect($url);
            //@TODO : Set message in session
            //$this->setRedirect($url, $msg);
            $result = true;
        } else {
            $error = $item->getStatusMessage();
            $context->response->setStatus(KHttpResponse::INTERNAL_SERVER_ERROR,
                $error ? $error : 'Unable to activate user');
            $result = false;
        }

        return $result;
    }

    protected function _beforeControllerAdd(KCommandContext $context) {
        if ($this->_enable) {
            // Set activation on new records.
            $password                  = $this->getService('com://admin/users.database.row.password');
            $context->data->activation = $password->getRandom(32);
            $context->data->enabled    = 0;
        }
    }
}