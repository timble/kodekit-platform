/**
 * @version		$Id$
 * @category    Koowa
 * @package     Koowa_Media
 * @subpackage  Javascript
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/** Requires */
//new Asset.javascript('./koowa.js', {id: 'Koowa'});


window.addEvent('domready', function() {
	$$('select.autoredirect').addEvent('change', function(){
		window.location = this.value;
	});
});
