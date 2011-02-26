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
 * Section Controller Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 */
class ComSectionsControllerSection extends ComDefaultControllerDefault
{
    /**
     * Deletes the dependent articles and categories
     *  
     * @see KControllerBread::_actionDelete()
     */
    protected function _actionDelete(KCommandContext $context)
    {
		$id = $context->caller->getModel()->getState()->id;

		if(!$this->_deleteDependents('categories','categories',array('section' => $id))) {
            return false;
        }

        if (!$this->_deleteDependents('content','articles',array('section' => $id))) {
            return false;
        }

        return parent::_actionDelete($context);
    }

    /**
     * Deletes dependent categories and content
     * 
     * @param string $option
     * @param string $name
     * @param array $filter array( 'state variable' => value)
     */
    protected function _deleteDependents($option, $name, $filter=null)
    {        
        $rowset = KFactory::tmp('admin::com.'.$option.'.model.'.$name)
                ->set($filter)->limit(0)->getList();

        $count = $rowset->count();
        $what  = ucfirst( ($count == 1) ? KInflector::singularize($name) : $name);
        
        if($count) 
        {
            if ( $result = $rowset->delete() ) {
                KFactory::get('lib.joomla.application')->enqueueMessage("$count $what deleted");
            } else {
                KFactory::get('lib.joomla.application')->enqueueMessage("Delete $what failed",'notice');
            }
        } 
        else $result = true;
        
        return $result;
    }
}