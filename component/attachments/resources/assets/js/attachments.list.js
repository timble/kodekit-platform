if(!Attachments) var Attachments = {};

Attachments.List = new Class({
    element : null,

    initialize: function(options) {
        this.element = document.id(options.container);
        this.url = options.action;
        this.token = options.token;
        this.coordinates = '';

        if(!this.element) {
            return;
        }

        this.addCrop();

        var that = this;
        this.element.getElements('a[data-action]').each(function(a) {
            if(a.get('data-action'))
            {
                a.addEvent('click', function(e) {
                    e.stop();
                    that.execute(this.get('data-action'), this.get('data-id'), this.get('data-row'));
                });
            }
        });
    },

    addCrop: function()
    {
        jQuery('#target').Jcrop({
            aspectRatio: 4 / 3,
            minSize: [200, 150],
            setSelect: [10, 10, 210, 160],
            onSelect: this.setCoordinates.bind(this),
            onChange: this.setCoordinates.bind(this)
        });
    },

    setCoordinates: function(c)
    {
        this.coordinates = c;
    },

    execute: function(action, id, row)
    {
        var method = '_action' + action.capitalize();

        if($type(this[method]) == 'function')
        {
            this.action = action;

            var uri = new URI(this.url);
            uri.setData('id', id);

            this[method].call(this, uri);
        }
    },

    _actionDelete: function(uri)
    {
        var form = new Koowa.Form({
            method: 'post',
            url: uri.toString(),
            params: {
                _action: 'delete',
                _token: this.token
            }
        });

        form.submit();
    },

    _actionCrop: function(uri)
    {
        var form = new Koowa.Form({
            method: 'post',
            url: uri.toString(),
            params: {
                _action: 'save',
                _token: this.token
            }
        });

        form.submit();
    }
});