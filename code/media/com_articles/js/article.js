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