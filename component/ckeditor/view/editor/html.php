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
            'baseHref'		         => '/files/'.$this->getObject('application')->getSite().'/',
            'language'			     => substr($language->getTag(), 0, strpos( $language->getTag(), '-' )),
            'contentsLanguage'       => substr($language->getTag(), 0, strpos( $language->getTag(), '-' )),
			'contentsLangDirectiony' => $language->isRTL() ? 'rtl' : 'ltr',
			'height' 				 => '',
			'width'					 => '',
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