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
 * Article View Javascript Behavior
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Javascript
 */

window.addEvent('domready', function() {
	var elSections = document.id('article-form-sections');
	var elCategories = document.id('article-form-categories');

	function setCategories(section) {
		elCategories.getChildren().dispose();

		categories[section].each(function(category) {
			elCategories.adopt(new Element('option', {
				'value' : category[0],
				'text' : category[1]
			}));
		});
		elCategories.fireEvent('change');
	}
	
	setCategories(elSections.get('value'));

	elSections.addEvent('change', function() {
		setCategories(elSections.get('value'));
	});
});