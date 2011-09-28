<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Banners Template Helper Listbox
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 */
class ComBannersTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{    
	public function category( $config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'identifier'  => 'com://admin/categories.model.categories',
			'name' 		  => 'category',
			'value'		  => 'id',
			'text'		  => 'title',
		    'filter'      => array('section' => 'com_banner')
		));

		return parent::_listbox($config);
	}
    
    /**
     * Image names helper
     * .
     * @param array() | KConfig $config
     * 
     * $config options
     * name         string      column name of helper
     * directory    string      image directory (relative to docroot)
     * filetypes    array       allowd file type extensions
     * deselect     boolean     show -select- option with 0 value
     * preview      boolean     show preview directly below listbox
     * selected     string      currently selected value
     * attribs      array       associative array of listbox attributes
     * 
     * see image_preview for image_preview options 
     */
    public function banner_names($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'      => 'image_name',
            'directory' =>  JPATH_IMAGES.'/banners',
            'filetypes' => array('swf', 'gif', 'jpg', 'png'),
            'deselect'  => true,
            'preview'   => true,
            'width'     => '',
            'height'    => '',
        ))->append(array(
                        'selected'  => $config->{$config->name}
        ))->append(array(
            'attribs' => array(
            'id' => $config->name,
            'class' => 'inputbox'
        )));  

        $root    = KRequest::root().str_replace(JPATH_ROOT, '', $config->directory);
        $default = KRequest::root().'/media/system/images/blank.png';
        $name    = $config->name;
        
        if (in_array('swf', $config->filetypes->toArray()))
        {
            JFactory::getDocument()->addScriptDeclaration("
            window.addEvent('domready', function(){
                var select = $('$name'), image = $('$name-preview'), flash = $('$name-flash'), x = $('$name-width'), y = $('$name-height'),
                    loadFlash = function() {
                        new Swiff('$root/' + select.value, {
                            id: flash.get('id')+'-movie',
                            container: flash.get('id'),
                            width: x.value || 150,
                            height: y.value || 150
                        });
                        flash.setStyle('display', 'block');
                        image.setStyle('display', 'none');
                    },
                    loadImage = function() {
                        image.src = select.value ? ('$root/' + select.value) : '$default';
                        image.setStyles({display: 'block', height: y.value.toInt(), width: x.value.toInt()});
                        flash.setStyle('display', 'none');
                    };
            
                $$(select, x, y).addEvent('change', function(){
                    select.value.test('^(.+).swf$') ? loadFlash() : loadImage();
                });
                $('$name-update').addEvent('click', function(event){event.preventDefault()});
            });
            ");
        } else {
            JFactory::getDocument()->addScriptDeclaration("
            window.addEvent('domready', function(){
                $('".$config->name."').addEvent('change', function(){
                    var value = this.value ? ('".$root."/' + this.value) : '".KRequest::root()."/media/system/images/blank.png';
                    $('".$config->name."-preview').src = value;
                });
            });
            ");
        }

        if($config->deselect) {
            $options[] = $this->option(array('text' => '- '.JText::_( 'Select' ).' -', 'value' => ''));
        }
  
        $files = array();
        foreach(new DirectoryIterator($config->directory) as $file) {
            if(in_array(pathinfo($file, PATHINFO_EXTENSION), $config->filetypes->toArray() )) {
                    $files[] = (string) $file;
            }
        }
        sort($files);
        foreach( $files as $file) {
            $options[] = $this->option(array('text' => (string) $file, 'value' => (string) $file));
        }

 
        $list = $this->optionlist(array(
            'options' => $options,
            'name'  => $config->name,
            'attribs' => $config->attribs,
            'selected' => $config->selected
        ));
        
        if (in_array('swf', $config->filetypes->toArray()))
        {
            $list .= ' <span>Banner size:</span> <label for="'.$config->name.'-width">'.JText::_('Width').'</label>
                        <input class="inputbox" type="text" name="width" 
                            id="'.$config->name.'-width" size="6"  
                            value="'.$config->width.'" /> '
                     .'<label for="'.$config->name.'-height">'.JText::_('Height').'</label>
                        <input class="inputbox" type="text" name="height" 
                            id="'.$config->name.'-height" size="6"  
                            value="'.$config->height.'" />'
                     ."<button id=\"".$config->name."-update\">".JText::_('update preview')."</button>"
                     ;
        }
        return $config->preview ? $list.'<br />'.$this->image_preview($config) : $list;
    }
    
    /**
     * 
     * Show banner preview
     * @param mixed $config
     * 
     * $config options:
     * name         string      column name of helper
     * directory    string      image directory (relative to docroot)
     * width        int         image width
     * height       int         image height
     * border       int         border width
     * style        string      style string
     * selected     string      currently selected vallue
     */
    public function banner_preview($config = array())
    {
        if(!$config instanceof KConfig){
            $config = new KConfig($config);
        }
        $config->append(array(
            'name'      => 'image_name',
            'directory' => JPATH_IMAGES.'/stories',
            'border'    => 2,
            'style'     => 'margin: 10px 0;'
        ))->append(array(
            'selected'  => $config->{$config->name}
        ));

        $preview = '<img '.KHelperArray::toString(array(
            'id'        => $config->name.'-preview',
            'class'     => 'preview',
            'border'    => $config->border,
            'alt'       => JText::_('Preview'),
            'style'     => $config->style
        )).' />'
        .'<div id="'.$config->name.'-flash"></div>'
        ;
        
        JFactory::getDocument()->addScriptDeclaration("
            window.addEvent('domready', function(){
                $('".$config->name."').fireEvent('change');
            });
        ");
        
        return $preview;
    }    
}