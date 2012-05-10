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
 * Captchable behavior
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersControllerBehaviorCaptchable extends KControllerBehaviorAbstract
{
    /**
     * @var string Private key.
     */
    protected $_private_key;

    /**
     * @var string Public key.
     */
    protected $_public_key;
    

    public function __construct(KConfig $config = null) 
    {
        if (!$config) {
            $config = new KConfig();
        }

        parent::__construct($config);

        if (is_null($config->private_key) || is_null($config->public_key)) {
            throw new KControllerBehaviorException('Public and/or private key(s) missing');
        }

        $this->_private_key = $config->private_key;
        $this->_public_key  = $config->public_key;
    }

    protected function _initialize(KConfig $config) 
    {
        $parameters = JComponentHelper::getParams('com_users');

        $config->append(array(
            'auto_mixin'  => true,
            'private_key' => $parameters->get('recaptcha_private_key', null),
            'public_key'  => $parameters->get('recaptcha_public_key', null)
        ));
        
        parent::_initialize($config);
    }

    protected function _beforeControllerEdit(KCommandContext $context) 
    {
        // Same as add.
        return $this->_beforeControllerAdd($context);
    }

    protected function _beforeControllerAdd(KCommandContext $context) 
    {
        if (!$this->captchaValid($context->data)) {
            // Prevent the action from happening.
            return false;
        }
    }

    protected function _beforeControllerGet(KCommandContext $context) {
        // Auto-set the public key in the view.
        $this->getView()->captcha_public_key = $this->_public_key;
    }

    /**
     * Checks if the provided captcha info is valid.
     *
     * @param
     *            KConfig The POST request data containing captcha information.
     *
     * @return boolean True on success, false otherwise.
     */
    public function captchaValid(KConfig $data) 
    {
        require_once (JPATH_LIBRARIES . '/recaptcha/recaptchalib.php');
        
        $result = recaptcha_check_answer($this->_private_key, KRequest::get('server.REMOTE_ADDR', 'raw'),
        $data->recaptcha_challenge_field, $data->recaptcha_response_field);

        if ($result->is_valid) {
            return true;
        }
        
        return false;
    }

}