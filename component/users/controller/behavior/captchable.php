<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Captchable Controller Behavior
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Nooku\Component\Users
 */
class ControllerBehaviorCaptchable extends Library\ControllerBehaviorAbstract
{
    /**
     * The last error message
     *
     * @var string The last error message.
     */
    protected $_error_message;

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'captcha'           => array(
                'private_key'       => null,
                'remote_ip'         => $this->getObject('request')->getAddress(),
                'verify_server'     => array(
                    'host' => 'www.google.com',
                    'path' => '/recaptcha/api/verify',
                    'port' => 80
                )
            )
        ));

        parent::_initialize($config);
    }

    /**
     * Submits an HTTP POST to a reCAPTCHA server
     *
     * @param array  $data The POST data
     * @return object The request response.
     */
    protected function _post($data)
    {
        $config =  $this->getConfig()->captcha;

        $content = array();
        foreach ($data as $key => $value) {
            $content[] = $key . '=' . urlencode(stripslashes($value));
        }

        $content = implode('&', $content);

        $request  = "POST {$config->verify_server->path} HTTP/1.0\r\n";
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

        // One TCP-IP packet
        $response = '';
        while (!feof($fs))  {
            $response .= fgets($fs, 1160);
        }
        fclose($fs);

        $response = explode("\r\n\r\n", $response, 2);

        return $response;
    }

    /**
     * Get the captcha error message
     *
     * @return string The last error message.
     */
    public function getCaptchaErrorMessage()
    {
        return $this->_error_message;
    }

    /**
     * Calls an HTTP POST function to verify if the user's guess was correct
     *
     * @param string $challenge The requested captcha string.
     * @param string $answer    The provided captcha string.
     * @return bool True if valid, false otherwise.
     */
    public function verifyCaptcha($challenge, $answer)
    {
        $config =  $this->getConfig()->captcha;

        if (!$private_key = $config->private_key) {
            throw new \UnexpectedValueException('reCAPTCHA private key is not set.');
        }

        if (!$remote_ip = $config->remote_ip) {
            throw new \UnexpectedValueException('reCAPTCHA remote ip is not set.');
        }

        if (!trim((string) $challenge) || !trim((string) $answer))
        {
            $this->_error_message = 'incorrect-captcha-sol';
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
                $this->_error_message = (string) $response[1];
            }
        }

        return $result;
    }

    protected function _beforeAdd(Library\ControllerContextInterface $context)
    {
        $result = true;
        $config =  $this->getConfig()->captcha;

        $challenge = $context->request->data->get('recaptcha_challenge_field', 'string');
        $answer    = $context->request->data->get('recaptcha_response_field', 'string');

        // Prevent the action from happening.
        if ($config->private_key && !$this->verifyCaptcha($challenge, $answer)) {
            $result = false;
        }

        return $result;
    }

    protected function _beforeEdit(Library\ControllerContextInterface $context)
    {
        // Same as add.
        return $this->_beforeAdd($context);
    }
}