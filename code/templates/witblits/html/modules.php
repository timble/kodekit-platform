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

jimport('joomla.application.module.helper');
 
 function moduleHeadings($modtitle)
 {
	// splitters
	$pretext = '\\';
	$subtext = '/';
	$twotone = '|';
	
	$spaces = array('<span class="twotone"> ', '<span class="pretext"> ', '<span class="subtext"> ', ' </span>');
	$nospaces = array('<span class="twotone">', '<span class="pretext">', '<span class="subtext">', '</span>');
	
	// Fix amps
	$modtitle = JFilterOutput::ampReplace($modtitle);
	
	
	// subtext & twotone
	if(strstr($modtitle, $subtext) && strstr($modtitle, $twotone)){
		$twotone_arr = explode($twotone, $modtitle);
		$subtext_arr = explode($subtext, $twotone_arr[1]);
		$str_twotone = $twotone_arr[0].' <span class="twotone">'.$subtext_arr[0].'</span>';
		$str_subtext = '<span class="subtext">'.$subtext_arr[1].'</span>';
		
		$string = $str_twotone .' '. $str_subtext;
		return str_replace($spaces, $nospaces, $string);
	}
	
	// subtext & twotone
	if(strstr($modtitle, $pretext) && strstr($modtitle, $twotone)){
		$pretext_arr = explode($pretext, $modtitle);
		$twotone_arr = explode($twotone, $pretext_arr[1]);
		$str_pretext = '<span class="pretext">'.$pretext_arr[0].'</span>';
		$str_twotone = $twotone_arr[0].' <span class="twotone">'.$twotone_arr[1].'</span>';
		
		$string = $str_pretext .' '. $str_twotone;
		return str_replace($spaces, $nospaces, $string);
	}
	
	if(strstr($modtitle, $twotone) && !strstr($modtitle, $pretext)){
		$twotone_arr = explode($twotone, $modtitle);
		$str_twotone = $twotone_arr[0].' <span class="twotone">'.$twotone_arr[1].'</span>';
		
		return str_replace($spaces, $nospaces, $str_twotone);
	}
	
	if(strstr($modtitle, $pretext) && !strstr($modtitle, $twotone)){
		$pretext_arr = explode($pretext, $modtitle);
		$str_pretext = '<span class="pretext">'.$pretext_arr[0].'</span> '.$pretext_arr[1];
		
		return str_replace($spaces, $nospaces, $str_pretext);
	}
	
	if(strstr($modtitle, $subtext) && !strstr($modtitle, $twotone)){
		$subtext_arr = explode($subtext, $modtitle);
		$str_subtext = $subtext_arr[0].'<span class="subtext">'.$subtext_arr[1].'</span>';
		
		return str_replace($spaces, $nospaces, $str_subtext);
	}
	
	if(!strstr($modtitle, $subtext) && !strstr($modtitle, $twotone)){
		return $modtitle;
	}
}

function modChrome_basic($module, &$params, &$attribs) 
{
	$pub_modules = JModuleHelper::getModules($module->position);

	if ($pub_modules[0]->id == $module->id) {
		$posSuffix = ' '.$params->get('moduleclass_sfx') . ' first';
	} elseif ($pub_modules[count($pub_modules)-1]->id == $module->id) {
		$posSuffix = ' '.$params->get('moduleclass_sfx') . ' last';
	} else {
		$posSuffix = ' '.$params->get('moduleclass_sfx');
	} ?>
	<div class="<?php if ($module->showtitle == 0) { ?>noheading <?php } ?>mod mod-basic<?php echo $posSuffix; ?>" id="mod<?php echo $module->id; ?>">
		<?php if ($module->showtitle != 0) : ?><h3 class="modhead"><span class="icon"></span><?php echo moduleHeadings($module->title); ?></h3><?php endif; ?>
		<div class="modinner">
		<?php echo $module->content; ?>
		</div>
	</div>
	<?php 
}

function modChrome_grid($module, &$params, &$attribs) 
{
	$pub_modules = JModuleHelper::getModules($module->position);
	
	if ($pub_modules[0]->id == $module->id) {
		$posSuffix = ' '.$params->get('moduleclass_sfx') . ' first';
	} elseif ($pub_modules[count($pub_modules)-1]->id == $module->id) {
		$posSuffix = ' '.$params->get('moduleclass_sfx') . ' last';
	} else {
		$posSuffix = ' '.$params->get('moduleclass_sfx');
	} ?>
	<div class="mod-grid<?php echo $posSuffix; ?>" id="mod<?php echo $module->id; ?>">
		<div class="mod">
			<?php if ($module->showtitle != 0) : ?><h3 class="modhead"><span class="icon"></span><?php echo moduleHeadings($module->title); ?></h3><?php endif; ?>
			<div class="modinner">
				<?php echo $module->content; ?>
			</div>
		</div>
	</div>
	<?php 
}

function modChrome_split($module, &$params, &$attribs) 
{
	$pub_modules = JModuleHelper::getModules($module->position);
	$menu = JSite::getMenu();
	$active_item = $menu->getActive();
	$parent_id = $active_item->tree[0];
	$parent_item = $menu->getItem($parent_id);
	$submenu_heading = $parent_item->name;
	$heading = explode(' # ',$submenu_heading);

	if ($pub_modules[0]->id == $module->id) {
		$posSuffix = ' '.$params->get('moduleclass_sfx') . ' first';
	} elseif ($pub_modules[count($pub_modules)-1]->id == $module->id) {
		$posSuffix = ' '.$params->get('moduleclass_sfx') . ' last';
	} else {
		$posSuffix = ' '.$params->get('moduleclass_sfx');
	} ?>
	<div class="mod mod-basic splitmenu<?php echo ' ' . $posSuffix; ?>" id="mod<?php echo $module->id; ?>">
    	<h3 class="modhead"><span class="icon"></span><?php echo $heading[0]; ?></h3>
		<div class="modinner">
	    	<?php echo $module->content; ?>
		</div>
	</div>
	<?php 
}