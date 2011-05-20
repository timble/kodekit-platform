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
    /**
     * Generates an HTML optionlist based on the distinct data from a model column.
     * 
     * The column used will be defined by the name -> value => column options in
     * cascading order. 
     * 
     * If no 'model' name is specified the model identifier will be created using 
     * the helper identifier. The model name will be the pluralised package name. 
     * 
     * If no 'value' option is specified the 'name' option will be used instead. 
     * If no 'text'  option is specified the 'value' option will be used instead.
     * 
     * @param   array   An optional array with configuration options
     * @return  string  Html
     * @see __call()
     */
    protected function _listbox($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'app'       => $this->getIdentifier()->application,
            'package'   => $this->getIdentifier()->package,
            'name'      => '',
            'state'     => null,
            'filter'    => array(),
            'attribs'   => array(),
            'model'     => null
        ))->append(array(
            'value'     => $config->name,
            'selected'  => $config->{$config->name}
        ))->append(array(
            'text'      => $config->value,
            'column'    => $config->value,
            'deselect'  => true
        ));
        
        $app        = $config->app;
        $package    = $config->package;
        $identifier = $app.'::com.'.$package.'.model.'.($config->model ? $config->model : KInflector::pluralize($package));
        
        $model = KFactory::tmp($identifier);
        foreach ($config->filter as $name => $value) {
            $model->set($name, $value);
        }
        $list = $model->getList($config->column);
        
        $options   = array();
        if($config->deselect) {
            $options[] = $this->option(array('text' => '- '.JText::_( 'Select').' -'));
        }
        
        foreach($list as $item) {
            $options[] =  $this->option(array('text' => $item->{$config->text}, 'value' => $item->{$config->value}));
        }
        
        //Add the options to the config object
        $config->options = $options;

        return $this->optionlist($config);
    }
    
    /**
     * Returns back a list of categories to choose from 
     */
    public function categories( $config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'app'       => 'admin',
            'package'   => 'categories',
            'model'     => 'categories',
            'name'      => 'catid',
            'value'     => 'id',
            'text'      => 'title',
            'filter' => array('section' => 'com_banner')
        ));

        return $this->_listbox($config);
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
            'directory' => 'images/stories',
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

        $root = KRequest::root().'/'.$config->directory;
        
        if (in_array('swf', $config->filetypes->toArray()))
        {
            KFactory::get('lib.joomla.document')->addScriptDeclaration("
            function loadFlash(config_name, value) {
                var w = $(config_name+'-width').getValue();
                if(w=='') w = '150';
                var h = $(config_name+'-height').getValue();
                if(h=='') h = '150';
                new Swiff('".$root."/' + value, {
                    id: config_name+'-flash-movie',
                    container: config_name+'-flash',
                    width: w,
                    height: h
                });
                $(config_name+'-flash').setStyle('display', 'block');
                $(config_name+'-preview').setStyle('display', 'none');
            }
            
            function loadImage(config_name, value) {
                value = value ? ('".$root."/' + value) : '".KRequest::root()."/media/system/images/blank.png';
                $('".$config->name."-preview').src = value;
                var w = $(config_name+'-width').getValue();
                if(w) $('".$config->name."-preview').width = w;
                else $('".$config->name."-preview').removeAttribute('width');
                var h = $(config_name+'-height').getValue();
                if(h) $('".$config->name."-preview').height = h;
                else $('".$config->name."-preview').removeAttribute('height');
                $(config_name+'-flash').setStyle('display', 'none');
                $(config_name+'-preview').setStyle('display', 'block');
            }
            
            window.addEvent('domready', function(){
                $('".$config->name."').addEvent('change', function(){
                    if (this.value.test('^(.+).swf$')) {
                        loadFlash('".$config->name."', this.value);
                    } else {
                        loadImage('".$config->name."', this.value);
                    }
                });
            });
            ");
        } else {
            KFactory::get('lib.joomla.document')->addScriptDeclaration("
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
        foreach(new DirectoryIterator(JPATH_SITE.'/'.$config->directory) as $file) {
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
                     ."<button onclick=\"$('".$config->name."').fireEvent('change');return false;\">".JText::_('update preview')."<button>"
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
            'directory' => 'images/stories',
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
        
        KFactory::get('lib.joomla.document')->addScriptDeclaration("
            window.addEvent('domready', function(){
                $('".$config->name."').fireEvent('change');
            });
        ");
        
        return $preview;
    }    
}