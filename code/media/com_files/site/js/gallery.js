/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

!function ($) {

    if(!this.ComFiles) this.ComFiles = {};

    // Class constructor
    var Gallery = function(gallery, options){

        //Used internally when 'this' is a context
        var self = this;

        this.options = $.extend({}, this.options, options);

        this.gallery    = gallery;
        this.thumbnails = gallery.find('.gallery-thumbnails');

        var thumb = this.thumbnails.find('li a .file-thumbnail img');
        if(thumb) {
            var styles = ['padding-left', 'padding-right', 'margin-left', 'margin-right', 'border-left-width', 'border-right-width'];

            $.each(styles, function(index, style){
                self.options.thumbwidth = self.options.thumbwidth + parseInt(thumb.css(style), 10);
            });
        }

        this.thumbnails.find('li a').css('width', this.options.thumbwidth);
        this.thumbnails.children().css({'margin-bottom': this.options.thumbspacing/2, 'margin-top': this.options.thumbspacing/2});

        this.setDimensions();

        if(this.options.fireOnResize) {
            $(window).on('resize', function(){
                self.setDimensions();
            });
            //Fire again once everything is loaded
            $(window).on('load', function(){
                self._cached_gallery_width = 0;
                self.setDimensions();
            });
        }

    };

    //Default class properties
    Gallery.prototype.options = {
        thumbwidth  : 200,
        thumbspacing: 10,
        fireOnResize: true
    };
    Gallery.prototype._cached_gallery_width = 0;

    //Class methods
    Gallery.prototype.setDimensions = function(){
        //Only fire if the cache have changed
        if(this._cached_gallery_width != this.thumbnails.width()) {
            var width = this.thumbnails.outerWidth(),
                factor = width/(this.options.thumbwidth+(this.options.thumbspacing*2)),
                limit = Math.floor(factor),
                thumbs = [[]],
                labels = [[]],
                index = 0,
                pointer = 0;

            this.thumbnails.children().each(function(i, element){
                var $element = $(element);
                $element.css('width', (100/limit)+'%');
                if(pointer == limit) {
                    pointer = 0;
                    if(!thumbs[++index]) thumbs[index] = [];
                    if(!labels[index])   labels[index] = [];
                }
                var label = $element.find('.file-label').css('min-height', '');
                thumbs[index][pointer]   = $element.find('.file-thumbnail');
                labels[index][pointer++] = label;
            });

            $.each(thumbs, function(row, items){
                var height = 0, hasLabel;
                $.each(items, function(i, item){
                    var img = item.find('img').length ? item.find('img') : item, y = img.outerHeight(),
                        offset = labels[row][i].length ? labels[row][i].outerHeight() : 0;
                    if((y - offset) > height) {
                        height = y;
                        hasLabel = labels[row][i].length;
                    }
                });
                $.each(items, function(i, item){
                    if(hasLabel) {
                        item.css('min-height', height);

                        var img = item.find('img');
                        if(img.is('img')) {
                            img.css('margin-top', Math.max((height - img.outerHeight())/2, 0));
                        }
                    } else {
                        var offset = labels[row][i].length ? labels[row][i].outerHeight() : 0;
                        item.css('min-height', Math.max(height - offset, item.css('min-height', '').outerHeight()));
                    }
                });
            });
            var tmp = $('<span/>');
            $.each(labels, function(row, items){
                var overflow = tmp;
                $.each(items, function(i, item){
                    if(item.height() > overflow.height()) overflow = item;
                });
                $.each(items, function(i, item){
                    if(item.length) {
                        item.css('min-height', overflow.height());
                    } else {
                        var thumb = thumbs[row][i];
                        if(!thumb) return;
                        var height = overflow.outerHeight()+overflow.parent().find('.file-thumbnail').outerHeight();
                        thumb.css('min-height', height);

                        var img = thumb.find('img');
                        if(img.is('img')) {
                            var offset = Math.max((height - img.outerHeight())/2, 0);
                            img.css('margin-top', offset);
                        }
                    }
                });
            });

            //Cache the gallery width to make window.onresize checks cheaper
            this._cached_gallery_width = this.gallery.width();
        }
    };

    //Attach class to global namespace
    this.ComFiles.Gallery = Gallery;

}(window.jQuery);