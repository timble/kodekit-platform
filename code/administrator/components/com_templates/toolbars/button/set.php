<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Default Toolbar Button class, sets a template as the default one
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates    
 */
class ComTemplatesToolbarButtonSet extends ComDefaultToolbarButtonDefault
{ 
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'text'     => JText::_('Make Default'),
        	'attribs'  => array(
                'data-action' => 'edit',
                'data-data'   => '{default:1}'
            )
        ));
        
        parent::_initialize($config);
    }
}