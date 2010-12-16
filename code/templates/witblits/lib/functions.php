<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Templates
 * @subpackage	Witblits
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('_JEXEC') or die('Restricted access');
require_once('templates/'.$this->template.'/lib/browser.php');
require_once JPATH_ROOT . '/templates/witblits/lib/witblitsLoader.php';

$tpath 						= JURI::root(1) . '/templates/'.$this->template;
$ipath	 					= JURI::root() . 'templates/'.$this->template.'/images/';
$apath						= JPATH_SITE.'/templates/'.$this->template;
$option 					= JRequest::getCmd('option');
$task 						= JRequest::getCmd('task');
$view 						= JRequest::getCmd('view');
$layout 					= JRequest::getCmd('layout');
$page 						= JRequest::getCmd('page');
$secid 						= JRequest::getCmd('secid');
$catid 						= JRequest::getCmd('catid');
$itemid 					= JRequest::getCmd('Itemid');
$doc 						= Jfactory::getDocument();
$user 						= JFactory::getUser();
$menus      				= JSite::getMenu();
$menu      					= $menus->getActive();
if (is_object( $menu )) :
	$params 				= new JParameter( $menu->params );
	$pageclass 				= $params->get( 'pageclass_sfx' );
endif;
$direction  				= $doc->direction;
$browser 					= new MBrowser();
$thebrowser 				= preg_replace("/[^A-Za-z]/i", "", $browser->getBrowser());
$ver 						= $browser->getVersion();
$dots 						= ".";
$dashes 					= "";
$mod_chrome					= "";
$ver 						= str_replace($dots , $dashes , $ver);
$lcbrowser 					= strtolower($thebrowser);

$wb_site_width				= $this->params->get('wb_site_width');
$wb_grid_columns			= $this->params->get('wb_grid_columns');
$wb_gridbg					= $this->params->get('wb_gridbg');
$wb_header_image			= $this->params->get('wb_header_image');
$wb_menu_position			= $this->params->get('wb_menu_position');
if($wb_header_image !== '-1') :
	$wb_header				= $ipath.'headers/'.$wb_header_image;
	$wb_header_size 		= getimagesize($wb_header);
	$wb_header_height 		= $wb_header_size[1].'px';
endif;
if($wb_header_image == '-1') :
	$wb_header				= '';
endif;
$wb_sidebar_columns			= $this->params->get('wb_sidebar_width_' . $wb_grid_columns);
$wb_content_columns			= $wb_grid_columns - $wb_sidebar_columns;
$wb_equalize				= $this->params->get('wb_equalize');
$wb_logo_type				= $this->params->get('wb_logo_type');
$wb_logo_text				= $this->params->get('wb_logo_text');
$wb_logo_link_title			= $this->params->get('wb_logo_link_title');
$wb_tagline					= $this->params->get('wb_tagline');
$wb_logo_image				= $this->params->get('wb_logo_image');
if($wb_logo_image !== '-1') :
	$wb_logo				= $ipath.'logos/'.$wb_logo_image;
	$wb_logo_size	 		= getimagesize($wb_logo);
	$wb_logo_width 			= $wb_logo_size[0].'px';
	$wb_logo_height 		= $wb_logo_size[1].'px';
endif;
if($wb_logo_image == '-1') :
	$wb_logo				= '';
	$wb_logo_width			= '';
	$wb_logo_height			= '';
endif;
$wb_tooltips				= $this->params->get('wb_tooltips');
$wb_sidebar_position		= $this->params->get('wb_sidebar_position');
$wb_blockwraps				= $this->params->get('wb_blockwraps');
$wb_breadcrumbs_position	= $this->params->get('wb_breadcrumbs_position');
$wb_show_date				= $this->params->get('wb_show_date');
$wb_skipto					= $this->params->get('wb_skipto');
$wb_color_background		= $this->params->get('wb_color_background');
$wb_color_foreground		= $this->params->get('wb_color_foreground');
$wb_color_h1				= $this->params->get('wb_color_h1');
$wb_color_h2				= $this->params->get('wb_color_h2');
$wb_color_h3				= $this->params->get('wb_color_h3');
$wb_color_h4				= $this->params->get('wb_color_h4');
$wb_color_h5				= $this->params->get('wb_color_h5');
$wb_color_h6				= $this->params->get('wb_color_h6');
$wb_googlefonts				= $this->params->get('wb_googlefonts');
$wb_heading_font			= $this->params->get('wb_heading_font');
$wb_color_text				= $this->params->get('wb_color_text');
$wb_color_links				= $this->params->get('wb_color_links');
$wb_color_links_hover		= $this->params->get('wb_color_links_hover');
$wb_user1_grid				= $this->countModules('user1');
$wb_user2_grid				= $this->countModules('user2');
$wb_user5_grid				= $this->countModules('user5');
$wb_user6_grid				= $this->countModules('user6');
$wb_user7_grid				= $this->countModules('user7');
$wb_user8_grid				= $this->countModules('user8');
$wb_inset2_grid				= $this->countModules('inset2');
$wb_inset3_grid				= $this->countModules('inset3');
$wb_gzip					= $this->params->get('wb_gzip');
$wb_css_packing				= $this->params->get('wb_css_packing');
$wb_js_packing				= $this->params->get('wb_js_packing');
$wb_editmode				= '';
$wb_footer_grid1			= '';
$wb_footer_grid2			= '';
$wb_grid					= '';
$wb_browser					= '';

if($wb_grid_columns == '12') :
	$wb_footer_grid1				= '7';
	$wb_footer_grid2				= '5';
elseif($wb_grid_columns == '16') :
	$wb_footer_grid1				= '10';
	$wb_footer_grid2				= '6';
elseif($wb_grid_columns == '24') :
	$wb_footer_grid1				= '16';
	$wb_footer_grid2				= '8';
endif;

function bodyClasses($menu, $view){
	$params = new JParameter($menu->params);
	$pageclass = $params->get('pageclass_sfx');
	$user = JFactory::getUser();
	$lang = JFactory::getLanguage();
	$browser = new MBrowser();
	$engine = strtolower(preg_replace("/[^A-Za-z]/i", "", $browser->getBrowser()));
	$version = $engine.str_replace('.', '', $browser->getVersion());
	$classes = array(
		'js-disabled',
		$engine,
		$version,
		strtolower($browser->getPlatform()),
		$params->get('pageclass_sfx'),
		$view,
		$lang->getTag(),
		Witblits::getTimeofday()
	);
	if($menu->query['option'] !== '') $classes[] = $menu->query['option'];
	
	//Classes based on user state and user type
	if($user->guest) $classes[] = 'user-guest';
	if($user->usertype) $classes[] = 'user-registered usertype-' . str_replace(array(' '), array('-'), strtolower($user->usertype));
	
	//Controller task
	if($task = JRequest::getCmd('task', false)) $classes[] = 'task-' . $task;
	
	return implode(' ', array_filter($classes));
}

$isiPhone		= strpos($browser, 'iphone') !== false;
$isiPad			= strpos($browser, 'ipad') !== false;
$isiPod			= strpos($browser, 'ipod') !== false;
$isBlackberry	= strpos($browser, 'blackberry') !== false;
$isAndroid		= strpos($browser, 'android') !== false;
$isFirefox		= strpos($browser, 'firefox') !== false;
$isSafari		= strpos($browser, 'safari') !== false;
$isChrome		= strpos($browser, 'chrome') !== false;
$isOpera		= strpos($browser, 'opera') !== false;
$isIe			= strpos($browser, 'ie') !== false;

// include the relevant grid css
if($wb_grid_columns == '24') :
	$wb_grid = '960_24col';
elseif($wb_grid_columns == 16 || $wb_grid_columns == 12) :
	$wb_grid = '960';
endif;

// include the relevant browser css
if($lcbrowser == 'firefox'){
	$wb_browser = 'firefox';
} elseif($lcbrowser == 'safari'){
	$wb_browser = 'safari';
} elseif($lcbrowser == 'chrome'){
	$wb_browser = 'chrome';
} elseif($lcbrowser == 'ie'){
	$wb_browser = 'ie';	
} elseif($lcbrowser == 'ipad'){
	$wb_browser = 'ipad';
} elseif($lcbrowser == 'ipod'){
	$wb_browser = 'ipod';
} elseif($lcbrowser == 'iphone'){
	$wb_browser = 'iphone';
} elseif($lcbrowser == 'blackberry'){
	$wb_browser = 'blackberry';
} elseif($lcbrowser == 'android'){
	$wb_browser = 'android';
}
$wb_cssfiles = "reset,$wb_grid,template,typo,joomla,modules,menus,tooltips,browsers/$wb_browser";
$wb_jsfiles = "menu,equalheights,tooltips,functions,modernizr";

if($wb_css_packing == 1) :
	$doc->addStyleSheet($tpath .'/lib/concatinate.php?filetype=css&amp;files='.$wb_cssfiles);
else :
	$doc->addStyleSheet($tpath .'/css/reset.css');
	if($wb_grid_columns == 16 || $wb_grid_columns == 12) :
	$doc->addStyleSheet($tpath .'/css/960.css');
	else :
	$doc->addStyleSheet($tpath .'/css/960_24col.css');
	endif;
	$doc->addStyleSheet($tpath .'/css/template.css');
	$doc->addStyleSheet($tpath .'/css/typo.css');
	$doc->addStyleSheet($tpath .'/css/joomla.css');
	$doc->addStyleSheet($tpath .'/css/modules.css');
	$doc->addStyleSheet($tpath .'/css/tooltips.css');
	$doc->addStyleSheet($tpath .'/css/menus.css');
	$doc->addStyleSheet($tpath .'/css/browsers/'.$wb_browser.'.css');
	if($wb_googlefonts == 1) $doc->addStyleSheet('http://fonts.googleapis.com/css?family='.str_replace(" ", "+", $wb_heading_font));
endif;

if($wb_js_packing == 1) {
	$doc->addScript($tpath .'/lib/concatinate.php?filetype=js&amp;files='.$wb_jsfiles);
} else {
	$doc->addScript($tpath .'/js/menu.js');
	$doc->addScript($tpath .'/js/equalheights.js');
	$doc->addScript($tpath .'/js/tooltips.js');
	$doc->addScript($tpath .'/js/modernizr.js');
	$doc->addScript($tpath .'/js/functions.js');
}

ob_start();
	if($wb_gridbg == 1) {
		echo "#grid-overlay{background:transparent url(templates/witblits/images/grid/$grid_columns-col.png) repeat-y top center;width:100%;height:100%;z-index:9999;position:fixed;left:0;}";
	}
	if($wb_color_background !== '') echo "html{background-color:$wb_color_background;}";
	if($wb_color_foreground !== '') echo "body{background-color:$wb_color_foreground;}";
	if($wb_googlefonts == 1) echo "#witblits h1, #witblits h2 {font-family: '".$wb_heading_font."', Arial, Helvetica, sans-serif;}";
	if($wb_color_h1 !== '') echo "#witblits h1{color:$wb_color_h1;}";
	if($wb_color_h2 !== '') echo "#witblits h2{color:$wb_color_h2;}";
	if($wb_color_h3 !== '') echo "#witblits h3{color:$wb_color_h3;}";
	if($wb_color_h4 !== '') echo "#witblits h4{color:$wb_color_h4;}";
	if($wb_color_h5 !== '') echo "#witblits h5{color:$wb_color_h5;}";
	if($wb_color_h6 !== '') echo "#witblits h6{color:$wb_color_h6;}";
	if($wb_color_text !== '') echo "body{color:$wb_color_text;}";
	if($wb_color_links !== '') echo "#witblits a,#witblits a:link,#witblits a:visited{color:$wb_color_links;}";
	if($wb_color_links_hover !== '') echo "#witblits a:hover{color:$wb_color_links_hover;}";
	if($wb_header !== '') echo "#witblits #header{background-image:url($wb_header);height:$wb_header_height;}";
$doc->addStyleDeclaration(ob_get_clean());

if($view=='article' && $task=='edit' || $view=='article' && $layout=='form') :
	$wb_article_form = " editmode";
else :
	$wb_article_form = "";
endif;
?>