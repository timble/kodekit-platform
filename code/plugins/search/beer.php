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
		'profiles' => 'Profiles'
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
	$db		=& JFactory::getDBO();
	$user	=& JFactory::getUser();

	if (is_array( $areas )) {
		if (!array_intersect( $areas, array_keys( plgSearchProfilesAreas() ) )) {
			return array();
		}
	}

	// load plugin params info
 	$plugin =& JPluginHelper::getPlugin('search', 'beer');
 	$pluginParams = new JParameter( $plugin->params );

	$limit = $pluginParams->def( 'search_limit', 50 );

	$text = trim( $text );
	if ($text == '') {
		return array();
	}

	$section = JText::_( 'Profiles' );

	switch ( $ordering ) {
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
	
	
	$text	= $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
	$list = KFactory::get('admin::com.beer.model.people')
		->setState('firstname', $text)
		->getList();

	//var_dump($list); die;

	return $list;
}
