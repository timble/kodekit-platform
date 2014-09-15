/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

Comments = new Class({
    Implements: Options,

    initialize: function(options) {
        var buttons = document.id(options.container).getElementsByClassName('icon-trash');
        this.setOptions(options);

        if(buttons.length)
        {
            buttons.each(function(button) {
                button.addEvent('click', function(e) {
                    e.stop();

                    if(confirm('Are you sure you want to delete this comment?')) {
                        this.deleteComment(button, options);
                    }
                }.bind(this));
            }.bind(this));
        }
    },

    deleteComment: function(button, options) {
        var id = button.getAttribute("data-id");

        jQuery.ajax({
            type: "delete",
            url: '?view=comment&id='+id,
            data: {
                id: id,
                csrf_token: options.data.csrf_token,
                _action: 'delete'
            },
            success: function(response){
                jQuery('#comment-'+id).remove()
            }
        });
    }
});