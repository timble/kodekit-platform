//@TODO docs


(function(){

    var hash = 'tab_package';
    
    if(window.localStorage) {

        if(!localStorage['installer.type']) {
            localStorage['installer.type'] = location.hash 
                                           ? location.hash.replace(/^\#/, '').replace('tab_', 'install_')
                                           : hash;
        } else {
            if(localStorage['installer.type'].match(/^tab_/)) {
                hash = localStorage['installer.type'];
            }
        }

        window.addEventListener('hashchange', function(){
            if(location.hash) {
                var hash = location.hash.replace(/^\#/, '');
                if(hash.match(/^tab_/)) {
                    localStorage['installer.type'] = hash;
                }
            }
        });

    }
    
    //Needs a hash by default
    if(!location.hash) location.hash = hash;
    
    window.addEvent('domready', function(){
        var buttons = $$('.-installer-form ul a');
    
        buttons.addEvent('click', function(event){
            if(event) event.preventDefault();

            buttons.removeClass('active');
            this.addClass('active');
            
            var hash = this.hash.replace(/^\#/, '').replace('tab_', 'install_');
            $$('.install-by').setStyle('display', 'none');
            $(hash).setStyle('display', 'block');
            switch (hash) {
                case 'install_directory':
                    var input = document.id(hash).getElement('input[type=text]');
                    if(input.setSelectionRange) {
                        setTimeout(function(){
                            var root = input.getProperty('data-root');
                            if(input.value.search(root) === 0) {
                                input.focus();
                                input.setSelectionRange(root.length+1, input.value.length);
                            } else {
                                input.select();
                            }
                        }, 1);
                    }
                    break;
                case 'install_url':
                    var input = document.id(hash).getElement('input[type=text]');
                    setTimeout(function(){
                        if(input.setSelectionRange && input.value.match(/^http\:\/\//)) {
                            input.focus();
                            input.setSelectionRange(7, input.value.length);
                        } else {
                            input.select();
                        }
                    }, 1);
                    break;
            }
            hash = hash.replace('install_', 'tab_');
            window.location.hash = hash;
            if(window.localStorage) localStorage['installer.type'] = hash;
        });
        
        buttons.each(function(button){
            if(button.hash == location.hash.replace('tab_', 'install_')) button.fireEvent('click', new Event);
        });
        
        var package = document.id('install_package');
        package.getElement('input[type=file]').addEvent('change', function(){
            package.addClass('selected');
        });
        package.getParent('form').addEvent('reset', function(){
            package.removeClass('selected');
        });
        package.getParent('form').getElements('input[type=text]').addEvent('keypress', function(event){
            if(event.key == 'enter') this.form.submit();
        });
    });
})();