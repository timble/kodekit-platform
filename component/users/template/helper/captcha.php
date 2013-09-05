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
 * Captcha Template Helper
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Users
 */
class TemplateHelperCaptcha extends Library\TemplateHelperDefault
{
    /**
     * Renders the reCAPTCHA widget.
     *
     * @param string  $error   The error message given by reCAPTCHA.
     * @param boolean $ssl     Determines is the request should be made over SSL.
     *
     * @return string - The HTML to be embedded in the user's form.
     */
    public function render($config = array())
    {
        $config = new Library\ObjectConfig($config);

        $params = $this->getObject('application.extensions')->users->params;

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

        $html = '';

        if ($public_key = $captcha->public_key) {
            if ($config->ssl) {
                $server = $captcha->api_secure_server;
            } else {
                $server = $captcha->api_server;
            }

            if ($config->error) {
                $config->error = '&amp;error=' . $config->error;
            }

            // Use options if any.
            if (count($options = $captcha->options)) {
                $options = Library\ObjectConfig::unbox($options);
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
        }

        return $html;
    }
}