<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Modules Filter
 * 
 * Filter to dynamically populate a module position from data rendered through a template.
 * 
 * Filter will parse elements of the form <modules position="[position]">[content]</modules> 
 * and prepend or append the content to the module position. 
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultTemplateFilterModule extends KTemplateFilterAbstract implements KTemplateFilterWrite
{  
    public function write(&$text)
    {   
		$matches = array();
		
		if(preg_match_all('#<module([^>]*)>(.*)</module>#siU', $text, $matches)) 
		{	
		    foreach($matches[0] as $key => $match)
			{
			    //Remove placeholder
			    $text = str_replace($match, '', $text);
			    
			    //Create attributes array
				$attributes = array(
					'style' 	=> 'component',
					'params'	=> '',	
					'title'		=> '',
					'class'		=> '',
					'prepend'   => true
				);
				
		        $attributes = array_merge($attributes, JUtility::parseAttributes($matches[1][$key])); 
				
		        //Create module object
			    $module   	       = new KObject();
			    $module->id        = 0;
				$module->content   = $matches[2][$key];
				$module->position  = $attributes['position'];
				$module->params    = $attributes['params'];
				$module->showtitle = !empty($attributes['title']);
				$module->title     = $attributes['title'];
				$module->attribs   = $attributes;
				$module->user      = 0;
				$module->module    = 'mod_dynamic';
				
			    KFactory::get('lib.joomla.document')->modules[$attributes['position']][] = $module;
			}
		}
		
		return $this;
    }    
}

/**
 * Modules Renderer
 * 
 * This is a specialised modules renderer which prepends or appends the dynamically created modules 
 * to the list of modules before rendering them.
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class JDocumentRendererModules extends JDocumentRenderer
{
	public function render( $position, $params = array(), $content = null )
	{
        //Get the modules
		$modules = JModuleHelper::getModules($position);
		
		if(isset($this->_doc->modules[$position])) 
		{
		    foreach($this->_doc->modules[$position] as $module) 
		    { 
		        if($module->attribs['prepend']) {
		            array_push($modules, $module);   
		        } else {
		            array_unshift($modules, $module);
		        }
		    }
		}
		
		//Render the modules
		$renderer = $this->_doc->loadRenderer('module');
		
		$contents = '';
		foreach ($modules as $module)  {
			$contents .= $renderer->render($module, $params, $content);
		}
		
		return $contents;
	}
}