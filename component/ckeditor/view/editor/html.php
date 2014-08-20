<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform
 */

namespace Nooku\Component\Ckeditor;

use Nooku\Library;

/**
 * Editor Html View Class
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Nooku\Component\Ckeditor
 */
class ViewEditorHtml extends Library\ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $locale = $this->getObject('translator')->getLocale();

        $config->append(array('settings' => array(
            'baseHref'		         => '/files/'.$this->getObject('application')->getSite().'/',
            'language'			     => substr($locale, 0, strpos( $locale, '-' )),
            'contentsLanguage'       => substr($locale, 0, strpos( $locale, '-' )),
			'height' 				 => '',
			'width'					 => '',
            'removeButtons'			 => '',
            'options'  => array(
                'autoheight'  => true,
                'toolbar'     => $this->toolbar ? $this->toolbar : 'standard',
            )
		)));

		parent::_initialize($config);
    }

    protected function _fetchData(Library\ViewContext $context)
	{
		if(!$context->data->id) {
		    $context->data->id = $context->data->name;
		}

        $settings = clone $this->getConfig()->settings;
        $settings->editor_selector = 'editable-'.$this->id;
        $settings->options->toolbar = $this->toolbar ? $this->toolbar : 'standard';

        if($this->removeButtons) {
            $settings->removeButtons = $this->removeButtons;
        }

		$context->data->settings = $settings;
        $context->data->class = isset($this->attribs['class']) ? $this->attribs['class'] : '';

		parent::_fetchData($context);
	}
}