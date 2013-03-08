<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Editor Html View Class
 *
 * @author  Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package Nooku\Component\Wysiwyg
 */
class ComWysiwygViewEditorHtml extends ComDefaultViewHtml
{
    protected $_editor_settings;
    
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);
        
        if ($config->editor_settings) {
            $this->_editor_settings = $config->editor_settings;
        }

        if (isset($config->codemirror)) {
            $this->codemirror = $config->codemirror;
        }
        if (isset($config->codemirrorOptions)) {
            $this->codemirrorOptions = $config->codemirrorOptions;
        }
    }

    protected function _initialize(Framework\Config $config)
    {
        $language = JFactory::getLanguage();

		$settings = array(
			'directionality'						=> $language->isRTL() ? 'rtl' : 'ltr',
			'editor_selector'						=> 'editable',
			'mode'									=> 'specific_textareas',
			'skin'									=> 'nooku',
			'theme'									=> 'advanced',
			'inline_styles'							=> true,
			'gecko_spellcheck'						=> true,
			'entity_encoding'						=> 'raw',
			'extended_valid_elements'				=> '',
			
			//'cleanup'								=> false,
			//'cleanup_on_startup'					=> false,
			//'force_br_newlines'				    => true,
			//'force_p_newlines'					=> false,
			//'forced_root_block'					=> false,
			//@TODO fix line breaks
			//'convert_newlines_to_brs'				=> false,
			
			'invalid_elements'						=> 'script,applet,iframe',
			'relative_urls'							=> false,
			'remove_script_host'					=> true,
			'document_base_url'						=>  Framework\Request::root().'/sites/'.$this->getService('application')->getSite(),
			'theme_advanced_toolbar_location'		=> 'top',
			'theme_advanced_toolbar_align'			=> 'left',
			'theme_advanced_source_editor_height'	=> '400',
			'height' 								=> '400',
			'width'									=> '100%',
			//'theme_advanced_source_editor_width'	=> $html_width,
			'theme_advanced_statusbar_location'		=> 'bottom',
			'theme_advanced_resizing'				=> false,
			'theme_advanced_resize_horizontal'		=> false,
			'theme_advanced_path'					=> true,
			'dialog_type'							=> 'modal',
			'language'								=> substr($language->getTag(), 0, strpos( $language->getTag(), '-' )),
			'theme_advanced_buttons1'				=> implode(',', array('bold', 'italic', 'strikethrough', 'underline', '|', 'bullist', 'numlist', 'blockquote', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', '|', 'link', 'unlink', '|', 'spellchecker', 'fullscreen', 'image', 'readmore', 'article', '|', 'advanced')),
			'theme_advanced_buttons2'				=> implode(',', array('formatselect', 'forecolor', '|', 'pastetext', 'pasteword', 'removeformat', '|', 'media', 'charmap', '|', 'outdent', 'indent', '|', 'undo', 'redo')),
			'theme_advanced_buttons3'				=> '',
			'theme_advanced_buttons4'				=> ''
		);
		
		$config->append(array(
			//Load CodeMirror in addition to TinyMCE
			'codemirror'   		=> true,
			'codemirrorOptions' => array(
				'stylesheet' => array(
					$config->media_url.'/wysiwyg/codemirror/lib/codemirror.css',
				  	/*$config->media_url.'/wysiwyg/codemirror/css/xmlcolors.css',
				  	$config->media_url.'/wysiwyg/codemirror/css/jscolors.css',
				  	$config->media_url.'/wysiwyg/codemirror/css/csscolors.css',
				  	$config->media_url.'/wysiwyg/css/codemirror.css'*/
				),
				'path' => $config->media_url.'/wysiwyg/codemirror/'
			),

			'editor_settings' => $settings
		));
		
		parent::_initialize($config);
    }
    
	public function render()
	{
		$options = new Framework\Config(array(
			'lang' => array(
				'html'		=> JText::_('HTML'),
				'visual'	=> JText::_('Visual')
			),
            'autoheight'        => true,
			'codemirror'        => $this->codemirror,
			'codemirrorOptions' => $this->codemirrorOptions,
			'toggle'            => $this->toggle
		));

		//@TODO cleanup
		if(!$this->id) {
		    $this->id = $this->name;
		}
		
		$this->setEditorSettings(array('editor_selector' => 'editable-'.$this->id));

		$this->options    = Framework\Config::unbox($options);
		$this->settings   = $this->getEditorSettings();
		$this->codemirror = $this->codemirror;

		return parent::render();
	}
	
    public function getEditorSettings()
	{
	    return Framework\Config::unbox($this->_editor_settings);
	}
	
	public function setEditorSettings(array $settings = array())
	{
	    foreach($settings as $key => $value) {
	        $this->_editor_settings->$key = $value;
	    }
	    
	    return $this;
	}
}