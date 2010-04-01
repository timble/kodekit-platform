<?php 
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */
 
/**
 * Template Media Helper
 *
 * Used for loading assets like javascript, stylesheets, images and 
 * other things you find in the extensions' media folder.
 * The assets are overridable in joomla templates,
 * and have a koowa fallback.
 *
 * When Koowa itself use this helper to load assets, 
 * then the extension can easily override things like the fw javascripts.
 * Useful if you're in for using jQuery for instance.
 *
 * @author		Stian Didriksen <stian@ninjaforge.com>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class ComProfilesHelperMedia extends KObject
{
	/**
	 * Get the full image path from a relative image path.
	 * 
	 * If the image don't exist, return a negative boolean so we can use it in conditional statements.
	 *
	 * This utility is on purpose not returning an image element, just the path.
	 * The usage examples show why this is.
	 *
	 * <code>
	 *
	 * // Used for an image element
	 * <img src="<?= @helper('media.img', '/logo.png') ?>" alt="App icon" />
	 * // May result in
	 * <img src="/media/com_profiles/images/logo.png" alt="App icon" />
	 *
	 * // Used in css, conditionally
	 * <style type="text/css">
	 * .icon-48-generic {
	 * <? if ( $img = @helper('media.img', '/logo.png') ) : ?>
	 *     background-url: url(<?= $img ?>);
	 * <? else : ?>
	 *     background-url: url(<?= KRequest::base() ?>/templates/khepri/images/header/icon-48-generic.png);
	 * <? endif ?>
	 * }
	 *
	 * </style>
	 *
	 * </code>
	 *
	 * @author	Stian Didriksen <stian@ninjaforge.com>
	 * @param	string $src
	 * @return	string | boolean
	 */
	public function img($src)
	{
		return self::_getAsset('images', $src);
	}
	
	/**
	 * Utility for adding internal stylesheets that are 3-step overridable (koowa, extension, joomla tempalte),
	 * adding remote stylesheets or by absolute urls,
	 * and for adding styledeclarations.
	 * 
	 * If you're adding an internal stylesheets, in other words by relative path,
	 * if the stylesheets turns out to not exist it'll return an boolean.
	 *
	 * You don't have to define wether you're passing an declaration, relative or absolute path.
	 * There's just one argument, and what you're passing to it is figured out by koowa auto-magically.
	 *
	 * <code>
	 *
	 * // External stylesheet
	 * <?= @helper('media.css', 'http://example.com/style.css') ?>
	 * // results in the following added to your document head
	 * <link rel="stylesheet" href="http://example.com/style.css" />
	 *
	 * // Internal stylesheet, with 3 potential results in com_foo
	 * <?= @helper('media.css', '/toolbar.css') ?>
	 * // 1. Overriden by template
	 * <link rel="stylesheet" href="/administrator/templates/khepri/css/com_foo/toolbar.css" />
	 * // 2. The component has it
	 * <link rel="stylesheet" href="/media/com_foo/css/toolbar.css" />
	 * // 3. Fallback to koowa
	 * <link rel="stylesheet" href="/media/plg_koowa/css/toolbar.css" />
	 *
	 * // Inline declaration
	 * <?= @helper('media.css', "
	 * // Removing firefox's ugly dotted border
	 * .toolbar a:focus { outline: none }
	 * ") ?>
	 * //Results in
	 * <style type="text/css">
	 * // Removing firefox's ugly dotted border
	 * .toolbar a:focus { outline: none }
	 * </style>
	 *
	 * </code>
	 *
	 * @author	Stian Didriksen <stian@ninjaforge.com>
	 * @param	string $href
	 * @return	string | boolean
	 */
	public function css($href = false)
	{
		$document = KFactory::get('lib.koowa.document');
		if	( KFactory::get('lib.koowa.filter.url')->validate($href) )
		{
			$document->addStylesheet($href);
		}
		else if	( strpos( $href, '{' ) )
		{
			$document->addStyleDeclaration($href);
		}
		else if	( $href = self::_getAsset('css', $href) )
		{
			$document->addStylesheet($href);
		}
		else
		{
			return false;
		}

		return $href;
	}
	
	/**
	 * Adds scripts the same way the css method does css.
	 *
	 * In addition to the 3-step flow the css helper got, the javascript helper got one extra step that makes it able to switch mode based on the current active js framework.
	 * Meaning you can have multiple versions of a script.
	 * Or you could have a special jQuery version of the toolbar js in Koowa, and only override Koowas when jQuery is active and much more!
	 * 
	 * If you're adding an internal scripts, in other words by relative path,
	 * if the stylesheets turns out to not exist it'll return an negative boolean.
	 *
	 * You don't have to define wether you're passing an declaration, relative or absolute path.
	 * There's just one argument, and what you're passing to it is figured out by koowa auto-magically.
	 *
	 * The current active js framework is fetched from the behavior.framework call.
	 * You can change the current framework by calling the following in your template layout:
	 * <code><? @helper('behavior.framework', 'jquery') ?></code>
	 * or this in your regular php
	 * KTemplate::loadHelper('behavior.framework', 'jquery');
	 *
	 * <code>
	 *
	 * // External script
	 * <?= @helper('media.js', 'http://example.com/app.js') ?>
	 * // results in the following added to your document head
	 * <script type="text/javascript" src="http://example.com/app.js"></script>
	 *
	 * // Internal script, with 5 potential results in com_foo
	 * <?= @helper('media.js', '/toolbar.js') ?>
	 * // 1. Overriden by template when jquery is active
	 * <script type="text/javascript" src="/administrator/templates/khepri/js/com_foo/jquery/toolbar.js"></script>
	 * // 2. Overriden by extension when jquery is active
	 * <script type="text/javascript" src="/media/com_foo/js/jquery/toolbar.js"></script>
	 * // 3. Overriden by template when the js isn't in a framework specific folder
	 * <script type="text/javascript" src="/administrator/templates/khepri/js/com_foo/toolbar.js"></script>
	 * // 4. The component has it but not in a framework specific folder
	 * <script type="text/javascript" src="/media/com_foo/js/toolbar.js"></script>
	 * // 5. Fallback to koowa
	 * <script type="text/javascript" src="/media/plg_koowa/js/toolbar.js"></script>
	 *
	 *
	 * // Inline declaration
	 * <?= @helper('media.js', "window.addEvent('domready', function(){ $('ice-cream').mixin(); });") ?>
	 * //Results in
	 * <script type="text/javascript">window.addEvent('domready', function(){ $('ice-cream').mixin(); });</script>
	 *
	 * </code>
	 *
	 * @author	Stian Didriksen <stian@ninjaforge.com>
	 * @param	string $href
	 * @return	string | boolean
	 */
	public function js($href = false)
	{
		$document = KFactory::get('lib.koowa.document');
		if(KFactory::get('lib.koowa.filter.url')->validate($href))
		{
			$document->addScript($href);
		}
		elseif(strpos($href, '(') || strpos($href, 'var') === 0)
		{
			$document->addScriptDeclaration($href);
		}
		elseif($src = self::_getAsset('js', '/'.KTemplate::loadHelper('behavior.framework').$href))
		{
			$document->addScript($href = $src);
		}
		elseif($href = self::_getAsset('js', $href))
		{
			$document->addScript($href);
		}
		else
		{
			return false;
		}

		return $href;		
	}
	
	/**
	 * Internal function used for getting assets paths for internal files.
	 *
	 * 3-step fallback. Joomla template => current extension => koowa.
	 * The asset argument can be a path, 
	 * as seen in the js method for giving overridability to framework specific js.
	 *
	 * @author	Stian Didriksen <stian@ninjaforge.com>
	 * @param	string $asset
	 * @param	string $url
	 * @return	string | boolean
	 */
	protected function _getAsset($asset, $url)
	{
		$extension = KRequest::get('get.option', 'cmd');
		$template  = KFactory::get('lib.koowa.application')->getTemplate();
		$framework = '/media/plg_koowa/'.$asset.$url;
		$default   = '/media/'.$extension.'/'.$asset.$url;
		$overriden = '/templates/'.$template.'/'.$extension.'/'.$asset.$url;

		if(file_exists(JPATH_BASE.$overriden))		return KRequest::base().$overriden;
		elseif(file_exists(JPATH_ROOT.$default))	return KRequest::root().$default;
		elseif(file_exists(JPATH_ROOT.$framework))	return KRequest::root().$framework;
        
		return false;
	}
	
	/**
	 * Magic method for loading other assets.
	 *
	 * Got the same 3-step fallback as images got.
	 * Can be very useful if you got a fonts folder, or have an upload api with progress bars using flash.
	 *
	 * <code>
	 *
	 * // Getting a flash player in com_player, got the same 3-step fallback as images, css and js got.
	 * <?= @helper('media.js', 'yt.setConfig(' . json_encode( array('SWF_URL' => @helper('media.swf', '/player.swf') ) ) . ');') ?>
	 * //results in
	 * <script type="text/javascript">
	 *		yt.setConfig({"SWF_URL":"/media/com_player/swf/player.swf"});
	 * </script>
	 *
	 * </code>
	 *
	 * @author	Stian Didriksen <stian@ninjaforge.com>
	 * @param	string $asset
	 * @param	string $url
	 * @return	string | boolean
	 */
	public function __call($m, $a) 
	{
		return self::_getAsset($m, $a[0]);
	}
}