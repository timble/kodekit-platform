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
 * Captcha template helper class
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersTemplateHelperCaptcha extends KTemplateHelperDefault
{
    /**
     * Renders the reCAPTCHA widget.
     *
     * @param string  $error   The error message given by reCAPTCHA.
     * @param boolean $ssl     Determines is the request should be made over SSL.
     *
     * @return string - The HTML to be embedded in the user's form.
     */
    public function render($config = array()) {

        $config = new KConfig($config);

        $params = $this->getService('application.components')->users->params;

        $config->append(array(
            'captcha'        => array(
                'public_key'        => $params->get('recaptcha_public_key', null),
                'api_server'        => 'http://www.google.com/recaptcha/api',
                'api_secure_server' => 'https://www.google.com/recaptcha/api',
                'options'           => array(
                    'theme' => 'clean',
                    'lang'  => 'en')),
            'error'          => '',
            'ssl'            => false));

        $captcha = $config->captcha;

        if (!$public_key = $captcha->public_key) {
            throw new InvalidArgumentException('public_key config option is required');
        }

        if ($config->ssl) {
            $server = $captcha->api_secure_server;
        } else {
            $server = $captcha->api_server;
        }

        if ($config->error) {
            $config->error = '&amp;error=' . $config->error;
        }

        $html = '';

        // Use options if any.
        if (count($options = $captcha->options)) {
            $options = KConfig::unbox($options);
            $html .= '<script type="text/javascript">';
            $html .= 'var RecaptchaOptions = ' . json_encode($options);
            $html .= '</script> ';
        }

        $html .= '<script data-inline type="text/javascript" src="' . $server . '/challenge?k=' . $public_key . $config->error . '"></script>
	<noscript>
  		<iframe src="' . $server . '/noscript?k=' . $public_key . $config->error . '" height="300" width="500" frameborder="0"></iframe><br/>
  		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
  		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
	</noscript>';

        return $html;
    }
}