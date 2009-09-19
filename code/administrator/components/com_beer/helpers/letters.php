<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 *
 * @version		$Id: letters.php
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * View helper for creating list with first letters of the name
 */
class BeerHelperLetters extends KObject
{
	public function firstnameletters($selected)
	{

		$firstnameletters = KFactory::get('admin::com.beer.model.viewfirstnameletters')->getList();
		

		$letters ='<div class="lettersmenu"><ul>';
		// a reset button, probably there is a better way doing this?
		$letters .= '<li><a ';
		$letters .=  'href="'.JRoute::_('index.php?option=com_beer&view=people&beer_firstnameletter_id=').'">';
		$letters .=  JText::_('Reset');
		$letters .=  '</a></li>';
		
		foreach (@$firstnameletters as $alfa) {
			$letters .= '<li><a ';
			if ($selected==$alfa->beer_firstnameletter_id){
				$letters .= 'class="active" ';
			}
			$letters .=  'href="'.JRoute::_('index.php?option=com_beer&view=people&beer_firstnameletter_id='.$alfa->beer_firstnameletter_id).'">';
			$letters .=  $alfa->beer_firstnameletter_id;
			$letters .=  '</a></li>';
		}

		$letters .='</ul>';
		return $letters;
	}
	
	public function lastnameletters($selected)
	{

		$lastnameletters = KFactory::get('admin::com.beer.model.viewlastnameletters')->getList();
	
		$letters ='<div class="lettersmenu"><ul>';
		// a reset button, probably there is a better way doing this?
		$letters .= '<li><a ';
		$letters .=  'href="'.JRoute::_('index.php?option=com_beer&view=people&beer_lastnameletter_id=').'">';
		$letters .=  JText::_('Reset');
		$letters .=  '</a></li>';
		
		foreach (@$lastnameletters as $alfa) {
			$letters .= '<li><a ';
			if ($selected==$alfa->beer_lastnameletter_id){
				$letters .= 'class="active" ';
			}
			$letters .=  'href="'.JRoute::_('index.php?option=com_beer&view=people&beer_lastnameletter_id='.$alfa->beer_lastnameletter_id).'">';
			$letters .=  $alfa->beer_lastnameletter_id;
			$letters .=  '</a></li>';
		}

		$letters .='</ul>';
		return $letters;
	}
}