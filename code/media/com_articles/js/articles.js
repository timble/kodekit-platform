/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Javascript
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Articles View Javascript Behavior
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Javascript
 */

window.addEvent('domready', function() {
	var elForm       = document.id('articles-form');
	
	['articles-form-created-by','articles-form-access'].each(function(item) {
		document.id(item).addEvent('change', function() {
			elForm.submit();
		})
	});
});