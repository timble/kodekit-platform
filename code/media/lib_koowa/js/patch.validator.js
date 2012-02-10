/*
---

description: Monkey patching the Form.Validator to alter its behavior and extend it into doing more

requires:
 - MooTools More

license: @TODO

...
*/

if(!Koowa) var Koowa = {};

(function($){
    
    Koowa.Validator = new Class({
    
        Extends: Form.Validator.Inline,
        
        options: {
            onShowAdvice: function(input, advice) {
                advice.addEvent('click', function(){
                    input.focus();
                });
            }
        }
    
    });

})(document.id);