<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Captchable Controller Behavior
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Users
 */
class ControllerBehaviorCaptchable extends Library\ControllerBehaviorAbstract
{
    /**
     * Captcha configuration object.
     *
     * @var ConfigCaptcha
     */
    protected $_config;

    /**
     * @var string The last error message.
     */
    protected $_error_message;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_config = $config->captcha;
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $params = $this->getObject('application.extensions')->users->params;

        $config->append(array(
            'auto_mixin'        => true,
            'captcha'           => array(
                'private_key'       => $params->get('recaptcha_private_key', null),
                'remote_ip'         => $this->getObject('application')->getRequest()->getAddress(),
                'verify_server'     => array(
                    'host' => 'www.google.com',
                    'path' => '/recaptcha/api/verify',
                    'port' => 80))
        ));

        parent::_initialize($config);
    }

    /**
     * Submits an HTTP POST to a reCAPTCHA server
     *
     * @param array  $data The POST data.
     *
     * @return object The request response.
     */
    protected function _post($data)
    {
        $config = $this->_config;

        $content = array();
        foreach ($data as $key => $value) {
            $content[] = $key . '=' . urlencode(stripslashes($value));
        }
        $content = implode('&', $content);

        $request = "POST {$config->verify_server->path} HTTP/1.0\r\n";
        $request .= "Host: {$config->verify_server->host}\r\n";
        $request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $request .= "Content-Length: " . strlen($content) . "\r\n";
        $request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $request .= "\r\n";
        $request .= $content;

        $fs = @fsockopen($config->verify_server->host, $config->verify_server->port, $errno, $errstr, 10);
        if ($fs === false) {
            throw new \RuntimeException('Could not open socket.');
        }

        fwrite($fs, $request);

        $response = '';
        while (!feof($fs)) {
            // One TCP-IP packet
            $response .= fgets($fs, 1160);
        }
        fclose($fs);

        $response = explode("\r\n\r\n", $response, 2);

        return $response;
    }

    /**
     * Error message setter.
     *
     * @param $message The error message.
     * @return ReCaptcha this.
     */
    protected function _setCaptchaErrorMessage($message)
    {
        $this->_error_message = $message;
        return $this;
    }

    /**
     * Error message getter.
     *
     * @return string The last error message.
     */
    public function getCaptchaErrorMessage()
    {
        return (string) $this->_error_message;
    }

    protected function _beforeControllerEdit(Library\CommandContext $context)
    {
        // Same as add.
        return $this->_beforeControllerAdd($context);
    }

    protected function _beforeControllerAdd(Library\CommandContext $context)
    {
        $result = true;

        $challenge = $context->request->data->get('recaptcha_challenge_field', 'string');
        $answer    = $context->request->data->get('recaptcha_response_field', 'string');

        // Prevent the action from happening.
        if ($this->_config->private_key && !$this->verifyCaptcha($challenge, $answer)) {
            $result = false;
        }

        return $result;
    }

    /**
     * Calls an HTTP POST function to verify if the user's guess was correct
     *
     * @param string $challenge The requested captcha string.
     * @param string $answer    The provided captcha string.
     *
     * @return bool True if valid, false otherwise.
     */
    public function verifyCaptcha($challenge, $answer)
    {
        $config = $this->_config;

        if (!$private_key = $config->private_key) {
            throw new \UnexpectedValueException('reCAPTCHA private key is not set.');
        }

        if (!$remote_ip = $config->remote_ip) {
            throw new \UnexpectedValueException('reCAPTCHA remote ip is not set.');
        }

        if (!trim((string) $challenge) || !trim((string) $answer))
        {
            $this->_setCaptchaErrorMessage('incorrect-captcha-sol');
            $result = false;
        }
        else
        {
            // Prepare the POST data.
            $data = array(
                'privatekey' => $private_key,
                'remoteip'   => $remote_ip,
                'challenge'  => $challenge,
                'response'   => $answer
            );

            $response = $this->_post($data);

            $response = explode("\n", $response [1]);

            if ($response[0] == 'false') {
                $result = false;
            } else {
                $result = true;
            }

            if (!$result) {
                $this->_setCaptchaErrorMessage((string) $response[1]);
            }
        }

        return $result;
    }
}