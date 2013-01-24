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

        list.getChildren().each(function(folder, index, siblings){
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
        options: {
            element: 'files-pathway',
        },
        initialize: function(element, options) {

            this.options.element = element;
        },
        setList: function(title, path, icon, app){

            if (!this.element) {

                this.element = this.options.element;
            }


        }
    });


})();