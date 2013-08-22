/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

if(!Ckeditor) var Ckeditor = {};

Ckeditor.Files = new Class({
    Extends: Files.App,
    Implements: [Events, Options],

    options: {
        types: ['file', 'image'],
        editor: null,
        preview: 'files-preview',
        grid: {
            cookie: false,
            layout: 'compact',
            batch_delete: false
        },
        history: {
            enabled: false
        }
    },

    initialize: function(options) {
        this.parent(options);

        this.editor = this.options.editor;
        this.preview = document.id(this.options.preview);
    },
    setPaginator: function() {
    },
    setPathway: function() {
    },
    setGrid: function() {
        var opts = this.options.grid;
        var that = this;
        $extend(opts, {
            'onClickImage': function(e) {
                var target = document.id(e.target),
                    node = target.getParent('.files-node-shadow') || target.getParent('.files-node');

                node.getParent().getChildren().removeClass('active');
                node.addClass('active');
                var row = node.retrieve('row');
                var copy = $extend({}, row);
                copy.template = 'details_image';

                that.preview.empty();

                copy.render('compact').inject(that.preview);

                that.preview.getElement('img').set('src', copy.image);

                var row     = target.getParent('.files-node').retrieve('row');
                var path    = row.baseurl+"/"+row.filepath;
                var url     = path.replace(Files.sitebase+'/', '').replace(/sites\/[^\/]+\//, '');

                document.id('image-url').set('value', url);
                document.id('image-type').set('value',row.metadata.mimetype);
            },
            'onClickFile': function(e) {
                var target = document.id(e.target),
                    node = target.getParent('.files-node-shadow') || target.getParent('.files-node');

                node.getParent().getChildren().removeClass('active');
                node.addClass('active');
                var row = node.retrieve('row');
                var copy = $extend({}, row);
                copy.template = 'details_file';

                that.preview.empty();

                copy.render('compact').inject(that.preview);

                var row     = target.getParent('.files-node').retrieve('row');
                var url     = row.image.replace(Files.sitebase+'/', '').replace(/sites\/[^\/]+\//, '');
                selected    = row.path;

                document.id('image-url').set('value', url);
                document.id('image-type').set('value',row.metadata.mimetype);
            }
        });
        this.grid = new Files.Grid(this.options.grid.element, opts);
    }
});
