/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

if (!Pages) {
    var Pages = {};
}

Pages.Module = new Class({
    Implements : Options,

    initialize : function(options) {
        this.setOptions(options);

        // If assignment has already changed, read changes from parent form and apply them.
        var value = this.options.parent_input_others.value;
        if (value) {
            switch (value) {
                case 'all':
                    this.options.form.getElement('input[name=pages][value=all]').set('checked', true);
                    this.options.form.getElements('input[name="page_ids[]"]').each(function(element) {
                        element.set('checked', true).set('disabled', true);
                    });
                    break;
                case 'none':
                    this.options.form.getElement('input[name=pages][value=none]').set('checked', true);
                    this.options.form.getElements('input[name="page_ids[]"]').each(function(element) {
                        element.set('checked', false).set('disabled', true);
                    });
                    break;
                default:
                    this.options.form.getElement('input[name=pages][value=selected]').set('checked', true);
                    value = JSON.decode(value);
                    this.options.form.getElements('input[name="page_ids[]"]').each(function(element) {
                        element.set('checked', value.contains(element.get('value').toInt())).set('disabled', false);
                    });
                    break;
            }
        }

        // Check if parent input has changed.
        var checked = this.options.parent_input_current.get('checked');
        var type = this.options.form.getElement('input[name=pages]:checked').get('value');
        switch (type) {
            case 'all':
                // If parent input is not checked, make type "selected" and uncheck the page.
                if (!checked) {
                    this.options.form.getElement('input[name=pages][value=selected]').set('checked', true);
                    this.options.form.getElement('input[name="page_ids[]"][value=' + this.options.page + ']').set('checked', false);
                    this.options.form.getElements('input[name="page_ids[]"]').each(function(element) {
                        element.set('disabled', false);
                    });
                }
                break;
            case 'none':
                // If parent input is checked, make type "selected" and check the page.
                if (checked) {
                    this.options.form.getElement('input[name=pages][value=selected]').set('checked', true);
                    this.options.form.getElement('input[name="page_ids[]"][value=' + this.options.page + ']').set('checked', true);
                    this.options.form.getElements('input[name="page_ids[]"]').each(function(element) {
                        element.set('disabled', false);
                    });
                }
                break;
            case 'selected':
                // Change page according to parent input.
                this.options.form.getElement('input[name="page_ids[]"][value=' + this.options.page + ']').set('checked', checked ? true : false);
                this.options.form.getElements('input[name="page_ids[]"]').each(function(element) {
                    element.set('disabled', false);
                });
                break;
        }
        
        // Add click event to types.
        this.options.form.getElement('input[name=pages][value=all]').addEvent('click', function() {
            this.options.form.getElements('input[name="page_ids[]"]').each(function(element) {
                element.set('checked', true).set('disabled', true);
            });
        }.bind(this));
        
        this.options.form.getElement('input[name=pages][value=none]').addEvent('click', function() {
            this.options.form.getElements('input[name="page_ids[]"]').each(function(element) {
                element.set('checked', false).set('disabled', true);
            });
        }.bind(this));
        
        this.options.form.getElement('input[name=pages][value=selected]').addEvent('click', function() {
            this.options.form.getElements('input[name="page_ids[]"]').each(function(element) {
                element.set('disabled', false);
            });
        }.bind(this));

        // Add click event to save button.
        this.options.form.getElement('input[name=save]').addEvent('click', function() {
            var value = this.options.form.getElement('input[name=pages]:checked').get('value');
            switch (value) {
                case 'all':
                    this.options.parent_input_current.set('checked', true);
                    break;
                case 'none':
                    this.options.parent_input_current.set('checked', false);
                    break;
                case 'selected':
                    // Collect the checked ids.
                    value = new Array();
                    this.options.form.getElements('input[name="page_ids[]"]:checked').each(function(element) {
                        value.push(element.get('value').toInt());
                    });
                    this.options.parent_input_current.set('checked', value.contains(this.options.page) ? true : false);
                    value = JSON.encode(value);
                    break;
            }

            // Insert the value into the parent form.
            this.options.parent_input_others.set('value', value);
            window.parent.SqueezeBox.close();
        }.bind(this));
    }
});