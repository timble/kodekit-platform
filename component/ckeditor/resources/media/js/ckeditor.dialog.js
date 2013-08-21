/**
 * @package     Nooku_Components
 * @subpackage  Ckeditor
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

if(!Ckeditor) var Ckeditor = {};

Ckeditor.Dialog = new Class({
    Implements: Options,

    initialize: function(options) {
        this.setOptions(options);

        if(this.options.type == 'file')
        {
            Files.app.grid.addEvent('clickFile', function(e) {
                var target  = document.id(e.target).getParent('.files-node');
                var row     = target.retrieve('row');
                var path    = row.baseurl+"/"+row.filepath;
                var url     = path.replace(Files.sitebase+'/', '').replace(/sites\/[^\/]+\//, '');

                document.id('image-url').set('value', url);
                document.id('image-type').set('value',row.metadata.mimetype);

                if(document.id('image-text').get('value') == ""){
                    document.id('image-text').set('value', row.name);
                }
            });
        }
        else
        {
            Files.app.grid.addEvent('clickImage', function(e) {
                var target  = document.id(e.target).getParent('.files-node');
                var row     = target.retrieve('row');
                var url     = row.image.replace(Files.sitebase+'/', '').replace(/sites\/[^\/]+\//, '');
                selected    = row.path;

                document.id('image-url').set('value', url);
                document.id('image-type').set('value',row.metadata.mimetype);
            });
        }
    }
});