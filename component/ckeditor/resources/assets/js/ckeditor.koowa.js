/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

if(!Koowa) var Koowa = {};

Koowa.Controller.Form = new Class({

    Extends: Koowa.Controller,

    _action_default: function(action, data, novalidate){
        if(!novalidate && !this.fireEvent('validate')) {
            return false;
        }

        // Don't validate if novalidate is set
        if(!novalidate){
            // Loop through all the editor intances
            // See: http://ckeditor.com/forums/CKEditor-3.x/Getting-CKEDITOR-instance
            for(var i in CKEDITOR.instances) {

                element = document.getElementById(CKEDITOR.instances[i].name);

                // If any instance is empty then abort the save action
                if(!CKEDITOR.instances[i].getData() && element.classList.contains('ckeditor-required')) {
                    return false;
                }
            }
        }

        this.form.adopt(new Element('input', {name: '_action', type: 'hidden', value: action}));
        this.form.submit();
    }
});