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
 * Captcha Template Helper
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Nooku\Component\Users
 */
class TemplateHelperCaptcha extends Library\TemplateHelperAbstract
{
    /**
     * Renders the reCAPTCHA widget.
     *
     * @return string - The HTML to be embedded in the user's form.
     */
    public function render($config = array())
    {
        $config = new Library\ObjectConfig($config);

        $config->append(array(
            'public_key' => null,
            'server'     => 'http://www.google.com/recaptcha/api',
            'error'      => '',
            'options'    => array(
                'theme' => 'clean',
                'lang'  => 'en'
            )
        ));

        $html = '';
        if ($public_key = $config->public_key)
        {
            if ($config->error) {
                $config->error = '&amp;error=' . $config->error;
            }

            // Use options if any.
            $options = Library\ObjectConfig::unbox($config->options);
            $html .= '<script>';
            $html .= 'var RecaptchaOptions = ' . json_encode($options);
            $html .= '</script> ';

            $html .= '<script data-inline type="text/javascript" src="' . $config->server . '/challenge?k=' . $public_key . $config->error . '"></script>';
	        $html .= '<noscript>';
  		    $html .= '<iframe src="' . $config->server . '/noscript?k=' . $public_key . $config->error . '" height="300" width="500" frameborder="0"></iframe><br/>';
  		    $html .= '<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>';
  		    $html .= '<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>';
	        $html .= '</noscript>';
        }

        return $html;
    }
}