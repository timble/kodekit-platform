<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Wysiwyg;

use Nooku\Library;

/**
 * Editor Html View Class
 *
 * @author  Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package Nooku\Component\Wysiwyg
 */
class ViewEditorHtml extends Library\ViewHtml
{
    protected $_editor_settings;
    
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        if ($config->editor_settings) {
            $this->_editor_settings = $config->editor_settings;
        }
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $language = \JFactory::getLanguage();

		$settings = array(
			'directionality'						=> $language->isRTL() ? 'rtl' : 'ltr',
			'editor_selector'						=> 'editable',
			'mode'									=> 'specific_textareas',
			'skin'									=> 'nooku',
			'scayt_autoStartup'						=> true,
			'entities'						        => false,
            'basicEntities'                         => false,
            'entities_greek'                        => false,
            'entities_latin'                        => false,
            'forcePasteAsPlainText'                 => true,
			'invalid_elements'						=> 'script,applet,iframe',
			'relative_urls'							=> false,
			'remove_script_host'					=> true,
			'document_base_url'						=>  $this->getObject('request')->getBaseUrl()->getPath().'/sites/'.$this->getObject('application')->getSite(),
			'height' 								=> '400',
			'width'									=> '',
			'dialog_type'							=> 'modal',
			'language'								=> substr($language->getTag(), 0, strpos( $language->getTag(), '-' )),
			'toolbar'				                => 'Standard',
		);

		$config->append(array(
			'editor_settings' => $settings
		));

		parent::_initialize($config);
    }
    
	public function render()
	{
		$options = new Library\ObjectConfig(array(
			'lang' => array(
				'html'		=> \JText::_('HTML'),
				'visual'	=> \JText::_('Visual')
			),
            'autoheight'        => true,
			'toggle'            => $this->toggle,
            'color'             => '',
            'toolbar'           => 'Standard',
		));

		//@TODO cleanup
		if(!$this->id) {
		    $this->id = $this->name;
		}
		
		$this->setEditorSettings(array('editor_selector' => 'editable-'.$this->id));

		$this->options    = Library\ObjectConfig::unbox($options);
		$this->settings   = $this->getEditorSettings();

		return parent::render();
	}
	
    public function getEditorSettings()
	{
	    return Library\ObjectConfig::unbox($this->_editor_settings);
	}
	
	public function setEditorSettings(array $settings = array())
	{
	    foreach($settings as $key => $value) {
	        $this->_editor_settings->$key = $value;
	    }
	    
	    return $this;
	}
}