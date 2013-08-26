<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Ckeditor;

use Nooku\Library;

/**
 * Editor Html View Class
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Nooku\Component\Ckeditor
 */
class ViewEditorHtml extends Library\ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $language = \JFactory::getLanguage();

        $config->append(array('settings' => array(
			'directionality'		=> $language->isRTL() ? 'rtl' : 'ltr',
			'editor_selector'		=> 'editable',
			'mode'					=> 'specific_textareas',
			'scayt_autoStartup'		=> true,
			'entities'				=> false,
            'basicEntities'         => false,
            'entities_greek'        => false,
            'entities_latin'        => false,
            'forcePasteAsPlainText' => true,
			'invalid_elements'		=> 'script,applet,iframe',
			'relative_urls'			=> false,
			'remove_script_host'	=> true,
			'document_base_url'		=>  $this->getObject('request')->getBaseUrl()->getPath().'/sites/'.$this->getObject('application')->getSite(),
			'height' 				=> '',
			'width'					=> '',
			'dialog_type'			=> 'modal',
			'language'			    => substr($language->getTag(), 0, strpos( $language->getTag(), '-' )),
            'options'  => array(
                'autoheight'  => true,
                'toolbar'     => $this->toolbar ? $this->toolbar : 'standard',
            )
		)));


		parent::_initialize($config);
    }
    
	public function render()
	{
		if(!$this->id) {
		    $this->id = $this->name;
		}

        $settings = clone $this->getConfig()->settings;
        $settings->editor_selector = 'editable-'.$this->id;
        $settings->options->toolbar = $this->toolbar ? $this->toolbar : 'standard';

		$this->settings = $settings;

		return parent::render();
	}
}