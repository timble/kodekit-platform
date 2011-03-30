<?php
/**
 * @version     $Id: default.php 2721 2010-10-27 00:58:51Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Html View
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultViewHtml extends KViewDefault
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
         
        //Add alias filter for editor helper
        KFactory::get($this->getTemplate())->getFilter('alias')->append(array(
            '@editor(' => '$this->loadHelper(\'admin::com.default.template.helper.editor.display\', ')
        );
        
        //Add the template override path
        $parts = $this->_identifier->path;
        
        array_shift($parts);
        if(count($parts) > 1) 
        {
            $path    = KInflector::pluralize(array_shift($parts));
            $path   .= count($parts) ? '/'.implode('/', $parts) : '';
            $path   .= DS.strtolower($this->getName()); 
        } 
        else $path  = strtolower($this->getName());
               
        $template = KFactory::get('lib.joomla.application')->getTemplate();
        $path     = JPATH_THEMES.'/'.$template.'/'.'html/com_'.$this->_identifier->package.DS.$path;
          
         KFactory::get($this->getTemplate())->addPath($path);
    }
}