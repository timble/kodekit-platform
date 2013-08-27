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
    setState: function() {
        // TODO: Implement pagination into the view
        this.fireEvent('beforeSetState');

        var opts = this.options.state;
        this.state = new Files.State(opts);

        this.fireEvent('afterSetState');
    },
    setGrid: function() {
        var opts = this.options.grid;
        var that = this;
        $extend(opts, {
            'onClickImage': function(e) {
                that.setPreview(document.id(e.target), 'image');

            },
            'onClickFile': function(e) {
                that.setPreview(document.id(e.target), 'file');
            }
        });
        this.grid = new Files.Grid(this.options.grid.element, opts);
    },
    setPreview: function(target, type) {
        var node    = target.getParent('.files-node-shadow') || target.getParent('.files-node');
        var row     = node.retrieve('row');
        var copy    = $extend({}, row);
        var path    = row.baseurl+"/"+row.filepath;
        var url     = path.replace(Files.sitebase+'/', '').replace(/files\/[^\/]+\//, '');

        // Update active row
        node.getParent().getChildren().removeClass('active');
        node.addClass('active');

        // Load preview template
        copy.template = 'details_'+type;
        this.preview.empty();
        copy.render('compact').inject(this.preview);

        // Inject preview image
        if (type == 'image') {
            this.preview.getElement('img').set('src', copy.image);
        }

        // When no text is selected use the file name
        if (type == 'file') {
            if(document.id('image-text').get('value') == ""){
                document.id('image-text').set('value', row.name);
            }
        }

        document.id('image-url').set('value', url);
        document.id('image-type').set('value',row.metadata.mimetype);
    }
});
