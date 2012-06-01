<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Form Filter
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultTemplateFilterForm extends KTemplateFilterForm
{
	protected function _tokenValue($force = false)
    {
        if(empty($this->_token_value) || $force) {  
            $this->_token_value = $this->getService('session')->getToken($force);
        }
        
        return parent::_tokenValue($force);
    }
}