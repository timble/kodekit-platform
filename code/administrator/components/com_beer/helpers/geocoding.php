<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerHelperGeocoding extends KObject
{
	public function coordinates($coordinate, $data)
 	{
		$query = str_replace(" ", "+", $data->address1 .'+'. $data->address2 .'+'. $data->postcode .'+'. $data->city .'+'. $data->country);
 		
 		// Desired address
		$geocoding = "http://maps.google.com/maps/geo?q=".$query."&output=xml";

	   // Retrieve the URL contents
	   $answer = file_get_contents($geocoding);
		// Parse the returned XML file

		$xml = new SimpleXMLElement($answer);
		
		// Parse the coordinate string
		list($longitude, $latitude, $altitude) = explode(",",$xml->Response->Placemark->Point->coordinates);
		
		switch ($coordinate) {
			case 'latitude':
				return (double) $latitude;
				break;
			case 'longitude':
				return (double) $longitude;
				break;
		}
 	}
}