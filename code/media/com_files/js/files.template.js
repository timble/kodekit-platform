(function() {

var cache = {};

Files.Template = new Class({
	render: function() {
		var layout = Files.Template.layout;
		var tmpl = layout+'_'+this.template;
		var rendered = new EJS({element: tmpl}).render(this);
		return new Files.Template[layout.capitalize()](rendered);
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