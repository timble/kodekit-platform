<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sections Template Listbox Helper Class
 *   
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 */
class ComSectionsTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
  	public function ordering( $config = array() )
   	{
     	$config = new KConfig($config);
       	$config->append(array(
          	'model' => 'sections',
           	'name' => 'ordering',
            'value' => 'ordering',
        	'text' => 'ordering',
          	'deselect' => false
       	));

      	return parent::_listbox($config);
 	}

   /**
     * Generates an HTML image position optionlist
     *
     * @param 	array   An optional array with configuration options
     * @return 	string  Html
     */
   	public function image_position($config = array())
   	{
       	$config = new KConfig($config);
      	$config->append(array(
          	'name'          => 'image_position',
           	'attribs'       => array(),
             'deselect'      => false
      	))->append(array(
           	'selected'  => $config->{$config->name}
       	));
		     
		$options  = array();
                
     	if($config->deselect) {
         	$options[] =  $this->option(array('text' => '- '.JText::_( 'Select' ).' -'));
       	}
                
      	$options[] = $this->option(array('text' => JText::_( 'Left' ), 'value' => 'left' ));
      	$options[] = $this->option(array('text' => JText::_( 'Center' ), 'value' => 'center' ));
       	$options[] = $this->option(array('text' => JText::_( 'Right' ), 'value' => 'right' ));
      	
       	//Add the options to the config object
       	$config->options = $options;
                
      	return $this->optionlist($config);
	}

	/**
	 * Image names helper
	 * .
	 * @param array() | KConfig $config
	 * 
	 * $config options
	 * name			string		column name of helper
	 * directory	string		image directory (relative to docroot)
	 * filetypes	array 		allowd file type extensions
	 * deselect		boolean		show -select- option with 0 value
	 * preview		boolean		show preview directly below listbox
	 * selected		string		currently selected value
	 * attribs		array		associative array of listbox attributes
	 * 
	 * see image_preview for image_preview options 
	 */
	public function image_names($config = array())
	{
  		$config = new KConfig($config);
  		$config->append(array(
   			'name'		=> 'image_name',
   			'directory'	=> 'images/stories',
  			'filetypes'	=> array('swf', 'gif', 'jpg', 'png'),
   			'deselect'	=> true,
  		    'preview'   => true
  		))->append(array(
                        'selected'  => $config->{$config->name}
		))->append(array(
			'attribs' => array(
			'id' => $config->name,
			'class' => 'inputbox'
			)));  

		$root = KRequest::root().'/'.$config->directory;

		KFactory::get('lib.joomla.document')->addScriptDeclaration("
		window.addEvent('domready', function(){
			$('".$config->name."').addEvent('change', function(){
				var value = this.value ? ('".$root."/' + this.value) : '".KRequest::root()."/media/system/images/blank.png';
				$('".$config->name."-preview').src = value;
			});
		});
		");

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
  		
  		return $config->preview ? $list.'<br />'.$this->image_preview($config) : $list;
 	}

 	/**
 	 * 
 	 * Show image preview img tag
 	 * @param mixed $config
 	 * 
 	 * $config options:
	 * name			string		column name of helper
	 * directory	string		image directory (relative to docroot)
 	 * width		int			image width
 	 * height		int			image height
 	 * border		int			border width
 	 * style		string		style string
 	 * selected		string		currently selected vallue
 	 */
 	public function image_preview($config = array())
 	{
 	    if(!$config instanceof KConfig){
 	        $config = new KConfig($config);
 	    }
 	    $config->append(array(
   			'name'		=> 'image_name',
   			'directory'	=> 'images/stories',
 	    	'width'		=> 80,
   			'height'	=> 80,
   			'border'	=> 2,
   			'style'		=> 'margin: 10px 0;'
  		))->append(array(
            'selected'  => $config->{$config->name}
 	    ));

 	    $path    = $config->selected ? KRequest::root().'/'.$config->directory.'/'.$config->selected : KRequest::root().'/media/system/images/blank.png';
  		$preview = '<img '.KHelperArray::toString(array(
  			'src'		=> $path,
  			'id'		=> $config->name.'-preview',
  			'class'		=> 'preview',
  			'width'		=> $config->width,
  			'height'	=> $config->width,
  			'border'	=> $config->border,
  			'alt'		=> JText::_('Preview'),
  			'style'		=> $config->style
  		)).' />';
 	    
 	    return $preview;
 	}
}