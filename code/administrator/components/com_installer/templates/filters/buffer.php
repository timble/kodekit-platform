<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Buffer Template Filter
 * 
 * @TODO
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComInstallerTemplateFilterBuffer extends KTemplateFilterAbstract implements KTemplateFilterWrite
{  
    /**
	 * @TODO
	 *
	 * @param string Block of text to parse
	 * @return ComInstallerTemplateFilterBuffer
	 */
    public function write(&$text)
    {   
		$matches = array();
		
		if(preg_match_all('#<buffer([^>]*)/>#siU', $text, $matches)) 
		{	
		    foreach($matches[0] as $key => $match)
			{   
			    //Create attributes array
				$attributes = array(
					'type' 	=> 'modules',
					'name'	=> '',	
					'render'=> ''
				);
				
		        $attributes = array_merge($attributes, JUtility::parseAttributes($matches[1][$key])); 
			    
			    $document = KFactory::get('joomla:document');
			    if($attributes['render']) {
			        $replace = $document->getBuffer($attributes['type'], $attributes['name']);
			    } else {
			        $buffer = $document->getBuffer();
			        $replace = $buffer[$attributes['type']][$attributes['name']];
			    }
			    $text = str_replace($match, $replace, $text);
			}
		}
		
		return $this;
    }    
}