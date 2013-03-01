if(!Attachments) var Attachments = {};

Attachments.List = new Class({
    element : null,

    initialize: function(options) {
        this.element = document.id(options.container);
        this.url = options.action;
        this.token = options.token;

        if(!this.element) {
            return;
        }

        var that = this;
        this.element.getElements('a[data-action]').each(function(a) {
            if(a.get('data-action'))
            {
                a.addEvent('click', function(e) {
                    e.stop();
                    that.execute(this.get('data-action'), this.get('data-id'));
                });
            }
        });
    },

    execute: function(action, data)
    {
        var method = '_action' + action.capitalize();

        if($type(this[method]) == 'function')
        {
            this.action = action;
            this[method].call(this, data);
        }
    },

    _actionDelete: function(id)
    {
        var uri = new URI(this.url);
        uri.setData('id', id);

        var form = new Koowa.Form({
            method: 'post',
            url: uri.toString(),
            params: {
                _action:'delete',
                _token: this.token
            }
        });
        form.submit();
    }
});