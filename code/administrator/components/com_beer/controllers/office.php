<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id$
 * @package		Beer
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access');
Koowa::import('admin::com.beer.controllers.abstract');

/**
 * Office Controller
 *
 * @package		Beer
 */
class BeerControllerOffice extends BeerControllerAbstract
{
	/**
	 * Filter input values, modify request
	 *
	 * @params	Arguments
	 * @return 	void
	 */
	public function filterInput($args)
	{
		$alias 			= KRequest::get('post.alias', 'ascii');
		$title 			= KRequest::get('post.title', 'string');
		$description	= KRequest::get('post.description', 'raw' );
		

			// Set query
			$address 	= KRequest::get('post.address1', 'string');
			$address 	= str_replace(" ", "+", $address);
			$city 		= KRequest::get('post.city', 'string');
			$postcode	= KRequest::get('post.postcode', 'string');

			$query = $address.'+'.$city;

			// Desired address
			$geocoding = "http://maps.google.com/maps/geo?q=" . $query . "&output=xml&oe=utf8&sensor=true";

			// Retrieve the URL contents
			$answer = file_get_contents($geocoding);

			// Parse the returned XML file
			$xml = new SimpleXMLElement($answer);

			// Parse the coordinate string
			list($longitude, $latitude, $altitude) = explode(",",$xml->Response->Placemark->Point->coordinates);

			$coordinates = $latitude . ',' . $longitude;

		if(empty($alias)) {
			$alias = KRequest::get('post.title', 'ascii');
		}

		KRequest::set('post.coordinates', $coordinates);
		KRequest::set('post.alias', $alias);
		KRequest::set('post.description', $description);
	}
}