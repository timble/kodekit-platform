<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Template Image Helper
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class KTemplateHelperImage extends KObject
{
	/**
	 * Creates a tooltip with an image as button
	 *
	 * @param	string	$tooltip The tip string
	 * @param	string	$title The title of the tooltip
	 * @param	string	$image The image for the tip, if no text is provided
	 * @param	string	$text The text for the tip
	 * @param	string	$href An URL that will be used to create the link
	 * @return	string
	 */
	public function tooltip($tooltip, $title = '', $image = 'tooltip.png', $text = '', $href = '')
	{
		$tooltip	= addslashes(htmlspecialchars($tooltip));
		$title		= addslashes(htmlspecialchars($title));

		if ( !$text ) {
			$image 	= KRequest::root().'/includes/js/ThemeOffice/'. $image;
			$text 	= '<img src="'. $image .'" border="0" alt="'. JText::_( 'Tooltip' ) .'"/>';
		} else {
			$text 	= JText::_( $text, true );
		}

		if($title) {
			$title = $title.'::';
		}

		$style = 'style="text-decoration: none; color: #333;"';

		if ( $href ) {
			$href = JRoute::_( $href );
			$style = '';
			$tip = '<span class="editlinktip hasTip" title="'.$title.$tooltip.'" '. $style .'><a href="'. $href .'">'. $text .'</a></span>';
		} else {
			$tip = '<span class="editlinktip hasTip" title="'.$title.$tooltip.'" '. $style .'>'. $text .'</span>';
		}

		return $tip;
	}
	
	/**
	 * Checks to see if an image exists in the current templates image directory
 	 * if it does it loads this image.  Otherwise the default image is loaded.
	 *
	 * @param	string	The file name, eg foobar.png
	 * @param	string	The path to the image
	 * @param	string	Alt text
	 * @param	array	An associative array of attributes to add
	 * @param	boolean	True (default) to display full tag, false to return just the path
	 */
	public function template( $file, $folder='media/', $alt = NULL, $attribs = null, $toHtml = 1)
	{
		static $paths;

		if (!$paths) {
			$paths = array();
		}

		if (is_array( $attribs )) {
			$attribs = KHelperArray::toString( $attribs );
		}

		$template = KFactory::get('lib.koowa.application')->getTemplate();
		$path = JPATH_BASE .'/templates/'. $template .'/images/'. $file;
		if (!isset( $paths[$path] ))
		{
			if ( file_exists( JPATH_BASE .'/templates/'. $template .'/images/'. $file ) ) {
				$paths[$path] = 'templates/'. $template .'/images/'. $file;
			} else {
				// outputs only path to image
				$paths[$path] = $folder . $file;
			}
		}
		$src = $paths[$path];
	
		// Prepend the base path
		$src = KRequest::base().'/'.$src;

		// outputs actual html <img> tag
		if ($toHtml) {
			return '<img src="'. $src .'" alt="'. html_entity_decode( $alt ) .'" '.$attribs.' />';
		}

		return $src;
	}
}