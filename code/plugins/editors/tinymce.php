<?php
/**
 * @version		$Id: tinymce.php 15099 2010-02-27 14:23:40Z ian $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Do not allow direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * TinyMCE WYSIWYG Editor Plugin
 *
 * @package Editors
 * @since 1.5
 */
class plgEditorTinymce extends JPlugin
{
	/**
	 * Method to handle the onInit event.
	 *  - Initializes the TinyMCE WYSIWYG Editor
	 *
	 * @access public
	 * @return string JavaScript Initialization string
	 * @since 1.5
	 */
	function onInit()
	{
		$mainframe =&JFactory::getApplication();
		$language =& JFactory::getLanguage();
		
		JPlugin::loadLanguage('plg_editors_tinymce', JPATH_ADMINISTRATOR);
		
		$mode  = $this->params->get('mode','advanced');
		$theme = array('simple' => 'simple','advanced' => 'advanced','extended' => 'advanced');
	
		$entity_encoding	= $this->params->def('entity_encoding', 'raw');

		if ($this->params->def('cleanup_startup', 0)) {
			$cleanup_startup = 'true';
		} else {
			$cleanup_startup = 'false';
		}
		
		switch ($this->params->def('cleanup_save', 2)) 
		{
			case '0': /* Never clean up on save */
				$cleanup = 'false';
				break;
				
			case '1': /* Clean up front end edits only */
				if ($mainframe->isadmin())
					$cleanup = 'false';
				else
					$cleanup = 'true';
				break;
				
			default:  /* Always clean up on save */
				$cleanup = 'true';
				break;
		}

		$langPrefix	= $this->params->def('lang_code', 'en');
		if ($this->params->def('lang_mode', 0)) {
			$langPrefix = substr($language->getTag(), 0, strpos( $language->getTag(), '-' ));
		}
		
		if ($language->isRTL()) {
			$text_direction = 'rtl';
		} else {
			$text_direction = 'ltr';
		}

		$use_content_css	= $this->params->def('content_css', 1);
		$content_css_custom	= $this->params->def('content_css_custom', '');

		/*
		 * Lets get the default template for the site application
		 */
		$template       = JComponentHelper::getParams('com_extensions')->get('template_site');
		$content_css    = '';
		$templates_path = JPATH_SITE.DS.'templates';
		
		// loading of css file for 'styles' dropdown
		if ( $content_css_custom )
		{
			// If URL, just pass it to $content_css
			if (strpos( $content_css_custom, 'http' ) !==false) 
			{
				$content_css = 'content_css : "'. $content_css_custom .'",';
				// If it is not a URL, assume it is a file name in the current template folder
			} 
			else 
			{
				$content_css = 'content_css : "'. JURI::root() .'templates/'. $template . '/css/'. $content_css_custom .'",';

				// Issue warning notice if the file is not found (but pass name to $content_css anyway to avoid TinyMCE error
				if (!file_exists($templates_path.DS.$template.DS.'css'.DS.$content_css_custom)) 
				{
					$msg = sprintf (JText::_('CUSTOMCSSFILENOTPRESENT'), $content_css_custom);
					JError::raiseNotice('SOME_ERROR_CODE', $msg);
				}
			}
		}
		else
		{
			// process when use_content_css is Yes and no custom file given
			if($use_content_css) 
			{
				// first check templates folder for default template
				// if no editor.css file in templates folder, check system template folder
				if (!file_exists($templates_path.DS.$template.DS.'css'.DS.'editor.css')) 
				{
					$template = 'system';

					// if no editor.css file in system folder, show alert
					if (!file_exists($templates_path.DS.'system'.DS.'css'.DS.'editor.css'))
					{
						JError::raiseNotice('SOME_ERROR_CODE', JText::_('TEMPLATECSSFILENOTPRESENT'));
					} else {
						$content_css = 'content_css : "' . JURI::root() .'templates/system/css/editor.css",';
					}
				} 
				else $content_css = 'content_css : "' . JURI::root() .'templates/'. $template . '/css/editor.css",';
			}
		}

		if ( $this->params->def('relative_urls', '1') ) { // relative
			$relative_urls = "true";
		} else { // absolute
			$relative_urls = "false";
		}

		if ( $this->params->def('newlines', 0)) { // br
			$forcenewline = "force_br_newlines : true, force_p_newlines : false, forced_root_block : '',";
		} else { // p
			$forcenewline = "force_br_newlines : false, force_p_newlines : true, forced_root_block : 'p',";
		}
		
		$invalid_elements	= $this->params->def('invalid_elements', 'script,applet,iframe');
		$extended_elements	= $this->params->def('extended_elements', '');

		// theme_advanced_* settings
		$toolbar 		= $this->params->def('toolbar'		, 'top');
		$toolbar_align	= $this->params->def('toolbar_align', 'left');
		$html_height 	= $this->params->def('html_height'	, '550');
		$html_width 	= $this->params->def('html_width'	, '750');
		$element_path 	= '';
		
		if ($this->params->get('element_path', 1)) {
			$element_path = 'theme_advanced_statusbar_location : "bottom", theme_advanced_path : true';
		} else {
			$element_path = 'theme_advanced_statusbar_location : "none", theme_advanced_path : false';
		}

		$plugins = array();
		if($extended_elements != "") {
			$elements = explode(',', $extended_elements);
		}

		// Plugins

		// paste
		if ($this->params->def('paste', 1)) {
			$plugins[]	= 'paste';
		}
	
		//media plugin
		if ($this->params->def('media', 1)) {
			$plugins[] = 'media';
		}
		
		// horizontal line
		if ($this->params->def('hr', 1)) {
			$plugins[]	= 'advhr';
			$elements[] = 'hr[id|title|alt|class|width|size|noshade|style]';
		} else {
			$elements[] = 'hr[id|class|title|alt]';
		}
		
		// fullscreen
		if ($this->params->def('fullscreen', 1)) {
			$plugins[]	= 'fullscreen';
		}
		
		// preview
		if ($this->params->def('preview', 1)) {
			$plugins[]	= 'preview';
		}
		
		// spellchecker
		if ($this->params->def('spellchecker', 1)) {
			$plugins[]	= 'spellchecker';
			$spellchecker_languages = '+English=en,Dutch=nl'; 
		}

		// style
		if ($this->params->def('style', 1)) {
			$plugins[]	= 'style';
		}
		
		// advimage
		if ($this->params->def('advimage', 1)) {
			$plugins[]	= 'advimage';
			$elements[]	= 'img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style]';
		}

		// advlink
		if ($this->params->def('advlink', 1)) {
			$plugins[]	= 'advlink';
			$elements[]	= 'a[id|class|name|href|target|title|onclick|rel|style]';
		}

		// autosave
		if ($this->params->def('autosave', 1)) {
			$plugins[]	= 'autosave';
		}

		// context menu
		if ( $this->params->def('contextmenu', 1)) {
			$plugins[]	= 'contextmenu';
		}

		// inline popups
		if ($this->params->def('inlinepopups', 1)) {
			$plugins[]	= 'inlinepopups';
			$dialog_type = "dialog_type : \"modal\",";
		} else {
			$dialog_type = "";
		}

		$buttons3 = array();

		// insert date and/or time plugin
		$insertdate			= $this->params->def('insertdate', 0);
		$format_date		= $this->params->def('format_date', '%Y-%m-%d');
		$inserttime			= $this->params->def('inserttime', 0);
		$format_time		= $this->params->def('format_time', '%H:%M:%S');
		
		if ($insertdate or $inserttime) 
		{
			$plugins[]	= 'insertdatetime';
			if ($insertdate) {
				$buttons3[]	= 'insertdate';
			}
			if ($inserttime) {
				$buttons3[]	= 'inserttime';
			}
			
			$buttons3[] = '|';
		}
		
		// search & replace
		if ($this->params->def('searchreplace', 1)) {
			$plugins[]	= 'searchreplace';
			$buttons3[]	= 'search,replace';
		}
		
		// table
		if ($this->params->def('table', 0)) {
			$plugins[]	= 'table';
			$buttons3[]	= 'table, tablecontrols, |';
		}
		
		// rtl/ltr buttons
		if ( $this->params->def('directionality', 0)) {
			$plugins[] = 'directionality';
			$buttons3[] = 'directionality';
		}

		// colors
		if ($this->params->def('colors', 0)) {
			$buttons3[] = 'forecolor,backcolor,|';
		}

		// XHTMLxtra
		if ($this->params->def('xhtmlxtras', 0)) {
			$plugins[]	= 'xhtmlxtras';
			$buttons3[]	= 'xhtmlxtras';
		}

		// visualchars
		if ($this->params->def('visualchars', 0)) {
			$plugins[]	= 'visualchars';
			$buttons3[]	= 'visualchars';
		}

		// non-breaking
		if ($this->params->def('nonbreaking', 0)) {
			$plugins[]	= 'nonbreaking';
			$buttons3[]	= 'nonbreaking';
		}

		// template
		if ($this->params->def('template', 0)) {
			$plugins[]	= 'template';
			$buttons3[]	= 'template';
		}

		// Prepare config variables
		$plugins = implode(',', $plugins);
		$elements = implode(',', $elements);
		
		//Prepare the 3th row of buttons based on the settings
		$buttons3 = implode(',', $buttons3);

		switch($mode) 
		{
			case 'simple':
				
				$load = "\t<script type=\"text/javascript\" src=\"".
						JURI::root(true).
						"/media/plg_tinymce/tiny_mce.js\"></script>\n";
				
				$return = $load .
				"\t<script type=\"text/javascript\">
				tinyMCE.init({
					// General
					directionality: \"$text_direction\",
					editor_selector : \"mce_editable\",
					language : \"". $langPrefix . "\",
					mode : \"specific_textareas\",
					theme : \"$theme[$mode]\",
					// Cleanup/Output
					inline_styles : true,
					gecko_spellcheck : true,
					cleanup : $cleanup,
					cleanup_on_startup : $cleanup_startup,
					entity_encoding : \"$entity_encoding\",
					$forcenewline
					// URL
					relative_urls : $relative_urls,
					remove_script_host : false,
					// Layout
					$content_css
					document_base_url : \"". JURI::root(true).'/sites/'.JFactory::getApplication()->getSite().'/'."\",
				});
				</script>";
				break;

			case 'advanced': 
				$load = "\t<script type=\"text/javascript\" src=\"".
						JURI::root(true).
						"/media/plg_tinymce/tiny_mce.js\"></script>\n";
				$return = $load .
				"\t<script type=\"text/javascript\">
				tinyMCE.init({
					// General
					$dialog_type
					directionality: \"$text_direction\",
					editor_selector : \"mce_editable\",
					language : \"". $langPrefix . "\",
					mode : \"specific_textareas\",
					plugins : \"$plugins\",
					theme : \"$theme[$mode]\",
					// Cleanup/Output
					inline_styles : true,
					gecko_spellcheck : true,
					spellchecker_languages : \"$spellchecker_languages\",
					cleanup : $cleanup,
					cleanup_on_startup : $cleanup_startup,
					entity_encoding : \"$entity_encoding\",
					extended_valid_elements : \"$elements\",
					$forcenewline
					invalid_elements : \"$invalid_elements\",
					// URL
					relative_urls : $relative_urls,
					remove_script_host : false,
					document_base_url : \"". JURI::root(true).'/sites/'.JFactory::getApplication()->getSite().'/'."\",
					// Layout
					$content_css
					// Advanced theme
					theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,|, bullist,numlist, outdent, indent\",
        			theme_advanced_buttons2 : \"link, unlink, |, image, ,media,charmap,|,anchor,hr,|,pastetext, pasteword, removeformat,cleanup, |, undo, redo,|, spellchecker, |, preview, fullscreen\",
        			theme_advanced_buttons3: \"$buttons3\",
					theme_advanced_toolbar_location : \"$toolbar\",
					theme_advanced_toolbar_align : \"$toolbar_align\",
					theme_advanced_source_editor_height : \"$html_height\",
					theme_advanced_source_editor_width : \"$html_width\",
					$element_path,
					plugin_insertdate_dateFormat : \"$format_date\",
					plugin_insertdate_timeFormat : \"$format_time\",
					fullscreen_settings : {
						theme_advanced_path_location : \"top\"
					}
				});
				</script>";
				break;
			}

		return $return;
	}

	/**
	 * TinyMCE WYSIWYG Editor - get the editor content
	 *
	 * @param string 	The name of the editor
	 */
	function onGetContent( $editor ) {
		return 'tinyMCE.get(\''.$editor.'\').getContent();';
	}

	/**
	 * TinyMCE WYSIWYG Editor - set the editor content
	 *
	 * @param string 	The name of the editor
	 */
	function onSetContent($editor, $html) {
		return 'tinyMCE.get(\''.$editor.'\').setContent('.$html.');';
	}

	/**
	 * TinyMCE WYSIWYG Editor - copy editor content to form field
	 *
	 * @param string 	The name of the editor
	 */
	function onSave($editor) {
 		return 'if (tinyMCE.get("'.$editor.'").isHidden()) {tinyMCE.get("'.$editor.'").show()}; tinyMCE.get("'.$editor.'").save();';
	}

	/**
	 * TinyMCE WYSIWYG Editor - display the editor
	 *
	 * @param string The name of the editor area
	 * @param string The content of the field
	 * @param string The width of the editor area
	 * @param string The height of the editor area
	 * @param int The number of columns for the editor area
	 * @param int The number of rows for the editor area
	 * @param mixed Can be boolean or array.
	 */
	function onDisplay($name, $content, $width, $height, $col, $row, $buttons = true)
	{
		// Only add "px" to width and height if they are not given as a percentage
		if (is_numeric( $width )) {
			$width .= 'px';
		}
		if (is_numeric( $height )) {
			$height .= 'px';
		}

		$editor  = $this->_displayButtons($name, $buttons);
		$editor .= $this->_toogleButton($name);
		$editor .= "<textarea id=\"$name\" name=\"$name\" cols=\"$col\" rows=\"$row\" style=\"width:{$width}; height:{$height};\" class=\"mce_editable\">$content</textarea>\n";
			

		return $editor;
	}

	function onGetInsertMethod($name)
	{
		$doc =& JFactory::getDocument();

		$js= "
			(function(){
				var initInvisible = Cookie.read('editor_$name') == 'html';
				if(initInvisible) {
					window.addEvent('domready', function(){
						$('$name').removeClass('mce_editable');
						window.fireEvent('editor.hide', '$name');
					});
				}
				window.addEvents({
					'editor.show': function(editor){
					    
					    if(document.getElement('#editor-toggle-buttons .visual').hasClass('selected')) return false;					    
					    
						if(initInvisible) {
							tinyMCE.execCommand('mceAddControl', false, editor);
							initInvisible = false;
						} else {
							tinyMCE.get(editor).show();
						}
						$$('#$name', '#".$name."_parent')
														.addClass('editor-visual')
														.removeClass('editor-html');

						document.getElement('#editor-toggle-buttons .visual')
																		.addClass('selected');
						document.getElement('#editor-toggle-buttons .html')
																		.removeClass('selected');
						
						Cookie.write('editor_$name', 'visual');
					},
					'editor.hide': function(editor){
					
					    if(document.getElement('#editor-toggle-buttons .html').hasClass('selected')) return false;
					
						if(tinyMCE.get(editor)) tinyMCE.get(editor).hide();
						$$('#$name', '#".$name."_parent')
														.removeClass('editor-visual')
														.addClass('editor-html');

						document.getElement('#editor-toggle-buttons .visual')
																		.removeClass('selected');
						document.getElement('#editor-toggle-buttons .html')
																		.addClass('selected');

						Cookie.write('editor_$name', 'html');
					}
				});
			})();

			function insertAtCursor(myField, myValue) {
				if (document.selection) {
					// IE support
					myField.focus();
					sel = document.selection.createRange();
					sel.text = myValue;
				} else if (myField.selectionStart || myField.selectionStart == '0') {
					// MOZILLA/NETSCAPE support
					var startPos = myField.selectionStart;
					var endPos = myField.selectionEnd;
					myField.value = myField.value.substring(0, startPos)
						+ myValue
						+ myField.value.substring(endPos, myField.value.length);
				} else {
					myField.value += myValue;
				}
			}
		
			function isBrowserIE() {
				return navigator.appName==\"Microsoft Internet Explorer\";
			}

			function jInsertEditorText( text, editor ) {
				insertAtCursor( document.getElementById(editor), text );
				if (isBrowserIE()) {
					if (window.parent.tinyMCE) {
						window.parent.tinyMCE.selectedInstance.selection.moveToBookmark(window.parent.global_ie_bookmark);
					}
				}
				tinyMCE.execInstanceCommand(editor, 'mceInsertContent',false,text);
			}

			var global_ie_bookmark = false;

			function IeCursorFix() {
				if (isBrowserIE()) {
					tinyMCE.execCommand('mceInsertContent', false, '');
					global_ie_bookmark = tinyMCE.activeEditor.selection.getBookmark(false);
				}
				return true;
			}";

		$doc->addScriptDeclaration($js);

		return true;
	}

	function _displayButtons($name, $buttons)
	{
		// Load modal popup behavior
		JHTML::_('behavior.modal', 'a.modal-button');

		$args['name'] = $name;
		$args['event'] = 'onGetInsertMethod';

		$return = '';
		$results[] = $this->update($args);
		foreach ($results as $result) {
			if (is_string($result) && trim($result)) {
				$return .= $result;
			}
		}

		if(!empty($buttons))
		{
			$results = $this->_subject->getButtons($name, $buttons);

			/*
			 * This will allow plugins to attach buttons or change the behavior on the fly using AJAX
			 */
			$return .= "\n<div id=\"editor-xtd-buttons\">\n";
			$return .= "\n<div class=\"left\">".JText::_('Upload/Insert:')."</div>";
			foreach ($results as $button)
			{
				/*
				 * Results should be an object
				 */
				if ( $button->get('name') )
				{
					$modal		= ($button->get('modal')) ? 'class="modal-button"' : null;
					$href		= ($button->get('link')) ? 'href="'.$button->get('link').'"' : null;
                    $onclick	= ($button->get('onclick')) ? 'onclick="'.$button->get('onclick').'"' : 'onclick="IeCursorFix(); return false;"';
					$return .= "<div class=\"button2-left\"><div class=\"".$button->get('name')."\"><a ".$modal." title=\"".$button->get('text')."\" ".$href." ".$onclick." rel=\"".$button->get('options')."\">".$button->get('text')."</a></div></div>\n";
				}
			}
			$return .= "</div>\n";
		}

		return $return;
	}

	function _toogleButton($name)
	{
		$return  = '';
		$return .= "\n<div id=\"editor-toggle-buttons\">\n";
		$return .= "<div class=\"visual selected\"><a href=\"#\" onclick=\"window.fireEvent('editor.show', '$name');return false;\" title=\"".JText::_('Visual')."\">".JText::_('Visual')."</a></div>";
		$return .= "<div class=\"html\"><a href=\"#\" onclick=\"window.fireEvent('editor.hide', '$name');return false;\" title=\"".JText::_('HTML')."\">".JText::_('HTML')."</a></div>";
		$return .= "</div>\n";
		return $return;
	}
}
