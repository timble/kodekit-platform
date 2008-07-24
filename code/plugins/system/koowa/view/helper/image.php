<?php
/**
 * @version		$Id$
 * @package		Koowa_View
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Image View Helper Class
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package		Koowa_View
 * @subpackage	Helper
 */
class KViewHelperImage
{
	/**
	 * Creates a tooltip with an image as button
	 *
	 * @param	string	$tooltip The tip string
	 * @param	string	$title The title of the tooltip
	 * @param	string	$image The image for the tip, if no text is provided
	 * @param	string	$text The text for the tip
	 * @param	string	$href An URL that will be used to create the link
	 * @param	boolean depreciated
	 * @return	string
	 * @since	1.5
	 */
	public static function tooltip($tooltip, $title='', $image='tooltip.png', $text='', $href='', $link=1)
	{
		$tooltip	= addslashes(htmlspecialchars($tooltip));
		$title		= addslashes(htmlspecialchars($title));

		if ( !$text ) {
			$image 	= JURI::root(true).'/includes/js/ThemeOffice/'. $image;
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
	* Also can be used in conjunction with the menulist param to create the chosen image
	* load the default or use no image
	*
	* @param	string	The file name, eg foobar.png
	* @param	string	The path to the image
	* @param	int		empty: use $file and $folder, -1: show no image, not-empty: use $altFile and $altFolder
	* @param	string	Another path.  Only used for the contact us form based on the value of the imagelist parm
	* @param	string	Alt text
	* @param	array	An associative array of attributes to add
	* @param	boolean	True (default) to display full tag, false to return just the path
	*/
	public static function site( $file, $folder='/images/M_images/', $altFile=NULL, $altFolder='/images/M_images/', $alt=NULL, $attribs = null, $asTag = 1)
	{
		static $paths;
		global $mainframe;

		if (!$paths) {
			$paths = array();
		}

		if (is_array( $attribs )) {
			$attribs = JArrayHelper::toString( $attribs );
		}

		$cur_template = $mainframe->getTemplate();

		if ( $altFile )
		{
			// $param allows for an alternative file to be used
			$src = $altFolder . $altFile;
		}
		else if ( $altFile == -1 )
		{
			// Comes from an image list param field with 'Do not use' selected
			return '';
		} else {
			$path = JPATH_SITE .'/templates/'. $cur_template .'/images/'. $file;
			if (!isset( $paths[$path] ))
			{
				if ( file_exists( JPATH_SITE .'/templates/'. $cur_template .'/images/'. $file ) ) {
					$paths[$path] = 'templates/'. $cur_template .'/images/'. $file;
				} else {
					// outputs only path to image
					$paths[$path] = $folder . $file;
				}
			}
			$src = $paths[$path];
		}

		if (substr($src, 0, 1 ) == "/") {
			$src = substr_replace($src, '', 0, 1);
		}

		// Prepend the base path
		$src = JURI::base(true).'/'.$src;

		// outputs actual html <img> tag
		if ($asTag) {
			return '<img src="'. $src .'" alt="'. html_entity_decode( $alt ) .'" '.$attribs.' />';
		}

		return $src;
	}

	/**
	* Checks to see if an image exists in the current templates image directory
	* if it does it loads this image.  Otherwise the default image is loaded.
	* Also can be used in conjunction with the menulist param to create the chosen image
	* load the default or use no image
	*
	* @param	string	The file name, eg foobar.png
	* @param	string	The path to the image
	* @param	int		empty: use $file and $folder, -1: show no image, not-empty: use $altFile and $altFolder
	* @param	string	Another path.  Only used for the contact us form based on the value of the imagelist parm
	* @param	string	Alt text
	* @param	array	An associative array of attributes to add
	* @param	boolean	True (default) to display full tag, false to return just the path
	*/
	public static function administrator( $file, $directory='/images/', $param=NULL, $param_directory='/images/', $alt = NULL, $attribs = null, $type = 1 )
	{
		global $mainframe;

		if (is_array( $attribs )) {
			$attribs = JArrayHelper::toString( $attribs );
		}

		$cur_template = $mainframe->getTemplate();

		// strip html
		$alt	= html_entity_decode( $alt );

		if ( $param ) {
			$image = $param_directory . $param;
		} else if ( $param == -1 ) {
			$image = '';
		} else {
			if ( file_exists( JPATH_ADMINISTRATOR .'/templates/'. $cur_template .'/images/'. $file ) ) {
				$image = 'templates/'. $cur_template .'/images/'. $file;
			} else {
				// compability with previous versions
				if ( substr($directory, 0, 14 )== "/administrator" ) {
					$image = substr($directory,15) . $file;
				} else {
					$image = $directory . $file;
				}
			}
		}

		if (substr($image, 0, 1 ) == "/") {
			$image = substr_replace($image, '', 0, 1);
		}

		// Prepend the base path
		$image = JURI::base(true).'/'.$image;

		// outputs actual html <img> tag
		if ( $type ) {
			$image = '<img src="'. $image .'" alt="'. $alt .'" '.$attribs.' />';
		}

		return $image;
	}
}