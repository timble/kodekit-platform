/*
Copyright (c) 2003 - 2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Plugin for inserting Drupal embeded media
 */
( function() {
  CKEDITOR.plugins.add( 'mediaembed',
  {
    requires : [ 'dialog', 'fakeobjects', 'htmlwriter' ],
    init: function( editor )
    {
      CKEDITOR.addCss(
        'img.cke_mediaembed' +
        '{' +
          'background-image: url(' + CKEDITOR.getUrl( this.path + 'images/placeholder.gif' ) + ');' +
          'background-position: center center;' +
          'background-repeat: no-repeat;' +
          'border: 1px solid #a9a9a9;' +
          'width: 80px;' +
          'height: 80px;' +
        '}'
        );
      var me = this;
      CKEDITOR.dialog.add( 'MediaEmbedDialog', function( editor ) {
        return {
          title :'Embed Media Dialog',
          minWidth : 400,
          minHeight : 200,
          contents : [
            {
              id : 'mediaTab',
              label :'Embed media code',
              title : 'Embed media code',
              elements :
              [
                {
                  id : 'embed',
                  type : 'textarea',
                  rows : 9,
                  label :'Paste embed code here'
                }
              ]
            }
          ],
          onOk : function() {
            var editor = this.getParentEditor();
            var content = this.getValueOf( 'mediaTab', 'embed' );
            if ( content.length>0 ) {
              var realElement = CKEDITOR.dom.element.createFromHtml('<div class="media_embed"></div>');
              realElement.setHtml(content);
              var fakeElement = editor.createFakeElement( realElement , 'cke_mediaembed', 'div', true);
              var matches = content.match(/width=\"?(\d+)\"?/i);
              if (matches && matches.length == 2) {
                fakeElement.setStyle('width', cssifyLength(matches[1]));
              }
              matches = content.match(/height=\"?(\d+)\"?/i);
              if (matches && matches.length == 2) {
                fakeElement.setStyle('height', cssifyLength(matches[1]));
              }
              editor.insertElement(fakeElement);
            }
          }
        };
      });

      editor.addCommand( 'MediaEmbed', new CKEDITOR.dialogCommand( 'MediaEmbedDialog' ) );

      editor.ui.addButton( 'MediaEmbed',
      {
        label: 'Embed Media',
        command: 'MediaEmbed',
        icon: this.path + 'images/icon.gif'
      } );
    },
    afterInit : function( editor )
    {
      var dataProcessor = editor.dataProcessor,
        dataFilter = dataProcessor && dataProcessor.dataFilter,
        htmlFilter = dataProcessor && dataProcessor.htmlFilter;

      if ( htmlFilter )
      {
        htmlFilter.addRules({
          elements :
          {
            'div' : function ( element ) {
              if( element.attributes['class'] == 'media_embed' ) {
                for (var x in element.children) {
                	if (typeof(element.children[x].attributes) != 'undefined') {
                		if (typeof(element.children[x].attributes.width) != 'undefined') {
                		   element.children[x].attributes.width = element.attributes.width;
                		}
                		if (typeof(element.children[x].attributes.height) != 'undefined') {
                		  element.children[x].attributes.height = element.attributes.height;
                		}
                	}
                }
              }
            }
          }
        });
      }
      if ( dataFilter )
      {
        dataFilter.addRules(
          {
            elements :
            {
              'div' : function( element )
              {
                var attributes = element.attributes,
                  classId = attributes.classid && String( attributes.classid ).toLowerCase();

                if (element.attributes[ 'class' ] == 'media_embed') {
                  var fakeElement = editor.createFakeParserElement(element, 'cke_mediaembed', 'div', true);
                  var fakeStyle = fakeElement.attributes.style || '';
                  if (typeof(element.children[0].attributes) != 'undefined') {
                	  var height = element.children[0].attributes.height,
                	  width = element.children[0].attributes.width;
                	  if ( typeof width != 'undefined' )
                		  fakeStyle = fakeElement.attributes.style = fakeStyle + 'width:' + cssifyLength( width ) + ';';



                	  if ( typeof height != 'undefined' )
                		  fakeStyle = fakeElement.attributes.style = fakeStyle + 'height:' + cssifyLength( height ) + ';';
                  }


                  return fakeElement;
                }
                return element;
              }
            }
          },
          5);
      }
    }
  } );
  var numberRegex = /^\d+(?:\.\d+)?$/;
  function cssifyLength( length )
  {
    if ( numberRegex.test( length ) )
      return length + 'px';
    return length;
  }
} )();
