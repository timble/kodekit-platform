/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
(function(){

    //This is a private function, we don't need it to be a method to Files.Pathway, or globally available in general
    var updatePathway = function(list, pathway, buffer, width, offset){

        var index = width - offset, sizes = buffer[index] || buffer.max, last = list.getChildren().length - 1;

        list.getChildren().each(function(folder, index){
            if(index > 0 && index < last) {
                folder.setStyle('width', sizes[index].value);
                if(sizes[index].value <= 48) {
                    folder.removeClass('overflow-ellipsis');
                } else {
                    folder.addClass('overflow-ellipsis');
                }
            }
        });

    };


    if (!this.Files) this.Files = {};

    Files.Pathway = new Class({
        Implements: [Options],
        element: false,
        options: {
            element: 'files-pathway',
            offset: 18
        },
        initialize: function(options) {
            this.setOptions(options);
        },
        setPath: function(app){

            if (!this.element) {

                this.element = document.id(this.options.element);
            }

            this.element.getParent().setStyle('position', 'relative');
            var pathway = this.element;
            pathway.setStyles({
                'overflow': 'visible',
                'text-overflow': 'ellipsis',
                'white-space': 'nowrap',
                bottom: 0,
                top: 0,
                left: 18,
                right: pathway.getNext().getSize().x + 18,
                'position': 'absolute'
            });
            pathway.empty();
            var list = new Element('ul', {'class': 'breadcrumb breadcrumb-resizable'}), wrap = function(app, title, path, icon){
                result = new Element('li', {
                    text: title,
                    title: title,
                    events: {
                        click: function(){
                            app.navigate(path);
                        }
                    }
                });
                if(icon) {
                    result.grab(new Element('span', {'class': 'divider', html: '<img src="media://com_files/images/arrow.png" width=8 height=19 />'}), 'top');
                }
                return result;
            };
            var root = wrap(app, ' '+app.container.title, '', false).grab(new Element('i', {'class': 'icon-hdd'}), 'top');
            list.adopt(root);
            var folders = app.getPath().split('/'), path = '';
            folders.each(function(title){
                if(title.trim()) {
                    path += path ? '/'+title : title;
                    list.adopt(wrap(app, title, path, true));
                }
            });
            list.getLast().addClass('active');

            pathway.setStyle('visibility', 'hidden');
            pathway.adopt(list);

            //Whenever the path changes, the buffer used in the resize handler is outdated, so have to be reattached
            if(this.pathway) {
                window.removeEvent('resize', this.pathway);

                this.pathway = false;
            }

            if(list.getChildren().length > 2) {

                var widths = {}, ceil = 0, offset = list.getFirst().getSize().x + list.getLast().getSize().x;
                list.getChildren().each(function(item, i){
                    if(item.match(':first-child') || item.match(':last-child')) return;
                    var x = item.getSize().x;
                    widths[i] = {key: i, value: x};
                    ceil += x;
                });

                //Create resize buffer
                var buffer = {}, queue = ceil;
                buffer[ceil] = buffer.max = widths;
                while(queue > 0) {
                    --queue;

                    var max = {key: null, value: 0}, sizelist = {};
                    for (var key in widths){
                        if (widths.hasOwnProperty(key)) {
                            var item = widths[key];
                            if(item.value > max.value) max = item;
                            sizelist[key] = {key: item.key, value: item.value};
                        }
                    }
                    --sizelist[max.key].value;

                    buffer[queue] = sizelist;
                    widths = sizelist;
                }

                updatePathway(list, pathway, buffer, pathway.getSize().x, offset);
                pathway.setStyle('visibility', 'visible');

                this.pathway = function(){
                    updatePathway(list, pathway, buffer, pathway.getSize().x, offset)
                };
                window.addEvent('resize', this.pathway);

            } else {
                pathway.setStyle('visibility', 'visible');
            }
        }
    });


})();