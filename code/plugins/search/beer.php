<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onSearch', 'plgSearchProfiles' );
$mainframe->registerEvent( 'onSearchAreas', 'plgSearchProfilesAreas' );

JPlugin::loadLanguage( 'plg_search_beer' );

/**
 * @return array An array of search areas
 */
function &plgSearchProfilesAreas()
{
	static $areas = array(
		'people' => 'People'
	);
	return $areas;
}

/**
* Beer Search method
*
* The sql must return the following fields that are used in a common display
* routine: href, title, section, created, text, browsernav
* @param string Target search string
* @param string mathcing option, exact|any|all
* @param string ordering option, newest|oldest|popular|alpha|category
*/
function plgSearchProfiles( $text, $phrase='', $ordering='', $areas=null )
{
	if (is_array( $areas )) 
	{
		if (!array_intersect( $areas, array_keys( plgSearchProfilesAreas() ) )) {
			return array();
		}
	}

	// load plugin params info
 	$pluginParams = new JParameter( JPluginHelper::getPlugin('search', 'beer')->params );
	$limit = $pluginParams->def( 'search_limit', 50 );

	if(($text = trim($text)) == '') {
		return array();
	}

	switch ( $ordering ) 
	{
		case 'alpha':
			$order = 'name ASC';
			break;

		case 'category':
			$order = 'office ASC, department ASC';
			break;

		case 'popular':
		case 'newest':
		case 'oldest':
		default:
			$order = 'name ASC';
	}


	//$text	= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
	$list = KFactory::get('admin::com.beer.model.people')
		->set('search', $text)
		->set('enabled', 1)
		//->set('order', $order) //TODO
		->getList();

	// Bit hackish. Don't blame me, com_search is a piece of M**bo crap
	$results = array();
	foreach($list as $k => $item)
	{
		$results[$k] = new stdClass;
		$results[$k]->href 		= 'index.php?option=com_beer&view=person&id='.$item->slug;
		$results[$k]->title	 	= $item->name;
		$results[$k]->created 	= $item->created;
		$results[$k]->section 	= JText::_('Office').': '.$item->office.', '.JText::_('Department').': '.$item->department;
		$results[$k]->text 		= $item->bio;
		$results[$k]->browsernav = 0;
	}
	return $results;
}
