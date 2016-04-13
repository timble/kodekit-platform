/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

if(!Attachments) var Attachments = {};

Attachments.List = new Class({
    element : null,

    initialize: function(options) {
        this.element = document.id(options.container);
        this.url = options.action;
        this.token = options.token;
        this.coordinates = '';
        this.trueSize = '';

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
        var target = jQuery('#target');
        var img = new Image(), self = this;

        img.onload = function() {
            self.trueSize = [this.width, this.height];

            if (target.length) {
                target.Jcrop({
                    boxWidth: 600,
                    boxHeight: 600,
                    trueSize: self.trueSize,
                    aspectRatio: 4 / 3,
                    minSize: [200, 150],
                    setSelect: [0, 0, 200, 150],
                    onSelect: self.setCoordinates.bind(self),
                    onChange: self.setCoordinates.bind(self)
                });
            }
        };

        var source = target.attr("src");
        if (source) {
            img.src = source;
        }

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
        var form = new Kodekit.Form({
            method: 'post',
            url: uri.toString(),
            params: {
                _action: 'delete',
                csrf_token: this.token
            }
        });

        form.submit();
    },

    _actionCrop: function(uri)
    {
        jQuery.ajax({
            url: uri.toString(),
            dataType: 'json',
            method: 'post',
            data: {
                _action: 'edit',
                csrf_token: this.token,
                x1: this.coordinates.x,
                y1: this.coordinates.y,
                x2: this.coordinates.x2,
                y2: this.coordinates.y2
            }
        }).then(function(data, textStatus, xhr) {
            if (xhr.status === 204) {
                jQuery.ajax({
                    url: uri.toString(),
                    dataType: 'json',
                    method: 'get'
                }).then(function(data, textStatus, xhr) {
                    if (xhr.status === 200 && typeof data.item === 'object') {
                        var thumbnail = data.item.thumbnail,
                            element   = window.parent.jQuery('.thumbnail[data-id="'+data.item.id+'"] img'),
                            source    = element.attr('src');

                        thumbnail = source.substring(0, source.lastIndexOf('/'))+'/'+thumbnail;

                        element.attr('src', thumbnail);

                        if (window.parent.SqueezeBox) {
                            window.parent.SqueezeBox.close();
                        }
                    }
                });
            } else {
                alert('Unable to crop thumbnail');
            }
        });
    }
});