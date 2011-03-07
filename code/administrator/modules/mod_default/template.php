<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Modules
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Module Template
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Modules
 * @subpackage  Default
 */
class ModDefaultTemplate extends KTemplateDefault
{
    /**
     * Method to set a view object attached to the template
     *
     * @param   mixed   An object that implements KObjectIdentifiable, an object that 
     *                  implements KIndentifierInterface or valid identifier string
     * @throws  KDatabaseRowsetException    If the identifier is not a table identifier
     * @return  KTemplateAbstract
     */
    public function setView($view)
    {
        if(!($view instanceof KViewAbstract))
        {
            $identifier = KFactory::identify($view);
        
            if($identifier->name != 'html') {
                throw new KViewException('Identifier: '.$identifier.' is not a view identifier');
            }
        
            $view = KFactory::get($identifier);
        }
        
        $this->_view = $view;
        return $this;
    }
}