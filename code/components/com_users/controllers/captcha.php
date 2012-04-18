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
    public function __construct(KConfig $config = null) 
    {
        if (!$config) {
            $config = new KConfig();
        }

        // TODO: Remove when the getActions problem at KControllerAbstract (http://ow.ly/a7jl3) is resolved:
        $this->getActions();

        parent::__construct($config);

        $this->registerCallback('before.get', array($this, 'setConfig'));
    }

    protected function _initialize(KConfig $config) 
    {
        $config->append(array('behaviors' => array('registrable')));
        
        parent::_initialize($config);
    }

    /**
     * Captcha config setter.
     *
     * @param
     *            KCommandContext The command context.
     */
    public function setConfig(KCommandContext $context) 
    {
        $config = $context->data;
        $config->append(array('public_key' => $this->getPublicKey()));
        
        // Set the captcha configuration available within the view.
        $this->getView()->assign('captcha', $config->toArray());
    }
}