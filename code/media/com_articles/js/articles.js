window.addEvent('domready', function() {
	var elForm       = document.id('articles-form');
	var elSections   = document.id('articles-form-section');
	var elCategories = document.id('articles-form-category');

	elSections.addEvent('change', function() {
		var section  = elSections.get('value');
		var category = elCategories.get('value');
		
		elCategories.set('value', section == '0' ? '0' : '-1')

		elForm.submit();
	});

	elCategories.addEvent('change', function() {
		var section  = elSections.get('value');
		var category = elCategories.get('value');
		
		if (category == '0') {
			elSections.set('value', 0);
		} else if (category == '-1' && section == '0') {
			elSections.set('value', '-1');
		}

		elForm.submit();
	});
	
	['articles-form-state','articles-form-created-by','articles-form-access'].each(function(item) {
		document.id(item).addEvent('change', function() {
			elForm.submit();
		})
	});
});