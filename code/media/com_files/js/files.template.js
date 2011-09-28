
if(!Files) var Files = {};

(function() {

var cache = {};

Files.Template = new Class({
	Implements: [Events],
	render: function() {
		var layout = Files.Template.layout,
			tmpl = layout+'_'+this.template;
		
		this.fireEvent('beforeRender', {layout: layout, template: tmpl});
		
		var rendered = new EJS({element: tmpl}).render(this);
		var result = new Files.Template[layout.capitalize()](rendered);

		this.fireEvent('afterRender', {layout: layout, template: tmpl, result: result});
		
		return result;
	}
});
Files.Template.layout = 'icons';

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