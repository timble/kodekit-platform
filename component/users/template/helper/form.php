<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Form Template Helper
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Nooku\Component\Users
 */
class TemplateHelperForm extends Library\TemplateHelperAbstract
{
    /**
     * Provides a password strength check layout.
     *
     * @param array $config An optional configuration array.
     * @return string The HTML layout.
     */
    public function password($config = array())
    {
        $translator = $this->getObject('translator');

        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'class'                    => 'help-block',
            'input_id'                 => 'password',
            'user_input_ids'           => array(),
            'words'                    => array(),
            'container_id'             => 'password-check',
            'min_score'                => 0,
            'min_score_msg'            => $translator('Please select a stronger password'),
            'score_map'                => array(
                '0' => $translator('Please provide a password'),
                '1' => $translator('Very weak'),
                '2' => $translator('Weak'),
                '3' => $translator('Good'),
                '4' => $translator('Strong'),
                '5' => $translator('Very strong'))));

        $options = array(
            'class'               => $config->class,
            'input_id'            => $config->input_id,
            'user_input_ids'      => $config->user_input_ids->toArray(),
            'words'               => $config->words->toArray(),
            'container_id'        => $config->container_id,
            'score_map'           => $config->score_map->toArray(),
            'min_score'           => $config->min_score,
            'min_score_msg'       => $config->min_score_msg);


        // Add required assets
        $html = '<ktml:script src="assets://users/js/users.js" />';

        $html .= '<span id="' . $config->container_id . '" class="' . $config->class . '">' . $config->score_map[0] . '</span>';

        // Async load of zxcvbn
        $zxcvbn_url = 'assets://users/js/zxcvbn/zxcvbn.js';
        $html .= '<script type="text/javascript">';
        $html .= '(function(){var a;a=function(){var a,b;b=document.createElement("script");b.src="' . $zxcvbn_url . '";b.type="text/javascript";b.async=!0;a=document.getElementsByTagName("script")[0];return a.parentNode.insertBefore(b,a)};null!=window.attachEvent?window.attachEvent("onload",a):window.addEventListener("load",a,!1)}).call(this);';
        $html .= '</script>';

        $html .= '<script type="text/javascript">';
        $html .= 'new ComUsers.Password.checker(' . json_encode($options) . ');';
        $html .= '</script>';

        return $html;
    }

}