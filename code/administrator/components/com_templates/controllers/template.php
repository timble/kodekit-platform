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
 * Template Controller Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates    
 */
class ComTemplatesControllerTemplate extends ComDefaultControllerDefault
{
    /**
     * Read action
     *
     * This functions loads the language file for a template
     *
     *  @return KDatabaseRow    A row object containing the selected row
     */
    protected function _actionRead(KCommandContext $context)
    {
        $template = parent::_actionRead($context);

        if(isset($template->name)) {
            KFactory::get('lib.joomla.language')->load('tpl_'.$template->name, JPATH_ADMINISTRATOR);
        }

        return $template;
    }

    /**
     * Edit action
     *
     * Takes care of storing params as well as menu assignments
     *
     *  @return KDatabaseRow    A row object containing the selected row
     */
    protected function _actionEdit(KCommandContext $context)
    {
        $templates = parent::_actionEdit($context);
        
        $menus     = KFactory::get('admin::com.templates.database.table.menus');
        $state     = $this->getModel()->getState();

        foreach($templates as $name => $template)
        {
            if(isset($template->params)) 
            {
                $params = KFactory::tmp('admin::com.templates.filter.ini')->sanitize($template->params);
                file_put_contents($template->ini_file, $params);
            }

            if(isset($template->selections) || (isset($template->menus) && $template->menus == 'none')) {
                $menus->select(array('client_id' => $state->client, 'template' => $name))->delete();
            }

            if(isset($template->selections))
            {
                foreach($template->selections as $selection)
                {
                    //Erase any potential previous assignments to this menu item before setting the new one
                    $menus->select(array('client_id' => $state->client, 'menuid' => $selection))->delete();

        		    $menus->getRow()
        		          ->setData(array(
        		              'client_id' => $state->client,
        		              'template'  => $name,
        		              'menuid'    => $selection
        		          ))
        		          ->save();
                }
        	}
        }

        return $templates;
    }
}