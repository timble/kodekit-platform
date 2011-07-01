/**
 * @version		$Id: weblinks.php 1294 2011-05-16 22:57:57Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Template
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Javascript loader
 *   
 * @author    	Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Template
 */

window.addEvent('domready', function(){
	if(Element.chromatable) $$('table.adminlist').chromatable();
	$$('td.divider').getPrevious().addClass('last');
});