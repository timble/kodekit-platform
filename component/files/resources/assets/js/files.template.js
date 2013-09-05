/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

if(!Files) var Files = {};

(function() {

var cache = {};

Files.Template = new Class({
	Implements: [Events],
	render: function(layout) {
		var tmpl = this.template;

		layout = layout || 'default';

		if (layout !== 'default') {
			tmpl = layout+'_'+tmpl;
		}

		this.fireEvent('beforeRender', {layout: layout, template: tmpl});

		var rendered = new EJS({element: tmpl}).render(this),
			result = new Files.Template[layout.capitalize()](rendered);

		this.fireEvent('afterRender', {layout: layout, template: tmpl, result: result});

		return result;
	}
});

Files.Template.Details = new Class({
	initialize: function(html) {
		var el = new Element('div', {html: html}).getElement('table');
		if (el) {
			return el;
		}
		else {
			var str = '<table><tbody>'+html+'</tbody></table>';
			return new Element('div', {html: str}).getElement('tr');
		}
	}
});

Files.Template.Default = new Class({
	initialize: function(html) {
		return new Element('div', {html: html}).getFirst();
	}
});

Files.Template.Icons = new Class({
	initialize: function(html) {
		return new Element('div', {html: html}).getFirst();
	}
});

Files.Template.Compact = new Class({
	initialize: function(html) {
		return new Element('div', {html: html}).getFirst();
	}
});

})();