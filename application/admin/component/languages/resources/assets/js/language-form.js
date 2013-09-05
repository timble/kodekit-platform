
function changeFlag(name) {
	var flag_url = '../media/nooku/images/flags/';
	$$('.nooku_flag')[0].setStyle('background-image', 'url('+flag_url+name.toLowerCase()+'.png)');
	$('image').value = name.toLowerCase()+'.png';
}

function restrictToAlpha(elem){
	var alpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	var result = '';
	for (i=0; i < elem.value.length; i++) {
		char = elem.value.charAt(i);
		if (alpha.indexOf(char,0) != -1) result += char;
	}
	elem.value = result;
}

function enableFieldset(fieldsetId, enable) {
	var set = $(fieldsetId);
	set.getElements('input')
		.extend(set.getElements('select'))
		.extend(set.getElements('label'))
		.each(function(item) {
			item.disabled = !enable;
		});
}

function enableButtons(enable) {
	var display = enable ? 'table-cell' : 'none';
	$('toolbar-save').style.display = display;
	$('toolbar-apply').style.display = display;
}

function populateLangFields(iso_code, name)
{
	var parts = iso_code.split('-', 2);
	
	$('name_field').value 	= name;
	$('native_field').value = name;
	$('alias_field').value	= parts[0];
	$('iso_code_lang_field').value		= parts[0];
	$('iso_code_country_field').value 	= parts[1];
}

window.addEvent('domready', function() {
    // iso code validation
    $$('.iso_code_field').addEvent('keyup', function(event){
    	restrictToAlpha(event.target);
    });
    
    // disable fieldset if needed
    if(! $('langpack').value) {
    	enableFieldset('lang_details', false);
    	enableButtons(false);
    }

	// behaviour when changing the langpack
    $('langpack').addEvent('change', function(event) {
    	var elem = event.target;
    	if('custom' == elem.value) {
    		enableFieldset('lang_details', true);
    		enableButtons(true);
   		} else if('install' == elem.value) {
   			enableFieldset('lang_details', false);
   			enableButtons(false);
   			top.location = 'option=com_installer';
  		} else if('' == elem.value) {
  			enableFieldset('lang_details', false);
  			enableButtons(false);
 		} else {
 			enableFieldset('lang_details', true);
 			enableButtons(true);
   			populateLangFields(elem.value, elem.options[elem.selectedIndex].text);
   			changeFlag($('iso_code_country_field').value);
   		}
    });
    
    // flag behaviour
    $('iso_code_country_field').addEvent('change', function(event) {
    	changeFlag(event.target.value);
    });
});

