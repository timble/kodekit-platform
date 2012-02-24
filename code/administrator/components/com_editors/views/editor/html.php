<?php

/**
 * @TODO clean up docs
 *
 * Usage example:
 * <?= @service('com://admin/editors.view.editor.html')->name('text')->data($article->text)->display() ?>
 */

class ComEditorsViewEditorHtml extends ComDefaultViewHtml
{
    protected $_settings, $_editors;
    
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        if ($config->settings) {
            $this->_settings = $config->settings;
        }

        if ($config->editors) {
            $this->_editors = $config->editors;
        }
    }

    /**
     * Initializes the configuration for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Configuration settings
     */
    protected function _initialize(KConfig $config)
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
			//'force_br_newlines'						=> true,
			//'force_p_newlines'						=> false,
			//'forced_root_block'						=> false,
			//@TODO fix line breaks
			//'convert_newlines_to_brs'				=> false,
			
			'invalid_elements'						=> 'script,applet,iframe',
			'relative_urls'							=> true,
			'remove_script_host'					=> false,
			'document_base_url'						=> KRequest::root(),
			'theme_advanced_toolbar_location'		=> 'top',
			'theme_advanced_toolbar_align'			=> 'left',
			'theme_advanced_source_editor_height'	=> '550',
			'height' 								=> '550',
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
			'layout'   => 'default',
			//@TODO this is because KControllerResource sets this and we have no controller yet
			'media_url' => KRequest::root().'/media',

			'editors'   => array('tinymce', 'codemirror'),

			'settings' => $settings
		));
		
		parent::_initialize($config);
    }
    
	public function display()
	{
		$options = array(
			'lang' => array(
				'html'		=> JText::_('HTML'),
				'visual'	=> JText::_('Visual')
			),
			'toggle' => $this->toggle
		);
		
		$this->assign('options' , $options);
		$this->assign('settings', KConfig::unbox($this->_settings));
		$this->assign('editors', KConfig::unbox($this->_editors));

		return parent::display();
	}
}