<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules    
 */

class ComModulesTemplateHelperListbox extends KTemplateHelperListbox
{
	/**
	 * Customized to add a few attributes.
	 *
	 * @TODO propose making the onchange attribute the default?
	 * 
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 * @see __call()
	 */
	protected function _listbox($config = array())
 	{
		$config = new KConfig($config);

		$config->append(array(
			//@TODO state isn't applied, work on patch later
			'state'		=> array(
				'application'	=> $config->application
			),
			'attribs'	=> array(
				'onchange' => 'this.form.submit()'
			)
		));

		return parent::_listbox($config);
 	}

 	/**
     * Generates a list over positions
     *
     * The list is the array over positions coming from the application template merged with the module positions currently in use
     * that may not be defined in the xml
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function positions($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'position' => 'left'
        ));
        
        $model = KFactory::get('admin::com.modules.model.modules');
        $query = KFactory::tmp('lib.koowa.database.query')
					->distinct()
				    ->select('template')
					->where('client_id', '=', (int)($model->getState()->application == 'admin'));

		//@TODO if com.templates is refactored to nooku, specifying the table name is no longer necessary
		$table		= KFactory::get('admin::com.templates.database.table.menu', array('name' => 'templates_menu'));
		$templates	= $table->select($query, KDatabase::FETCH_FIELD_LIST);
		$modules	= $model->getColumn('position');
		$positions	= $modules->getColumn('position');
		$root		= $model->getState()->application == 'admin' ? JPATH_ADMINISTRATOR : JPATH_ROOT;

        $template   = KFactory::tmp('admin::com.templates.model.templates')
                          ->application($model->getState()->application)
                          ->default(1)
                          ->getItem();

		foreach($template->positions as $position)
		{
			if(!in_array((string)$position, $positions)) {
				$positions[] = (string)$position;
			}
		}

		$positions = array_unique($positions);
		sort($positions);
        
        // @TODO combobox behavior should be in the framework
        JHTML::_('behavior.combobox');
        
        $html[] = '<input type="text" id="position" class="combobox" name="position" value="'.$config->position.'" />';
        $html[] = '<ul id="combobox-position" style="display:none;">';
        
        foreach($positions as $position) {
        	$html[] = '<li>'.$position.'</li>';
        }
        $html[] = '</ul>';

        return implode(PHP_EOL, $html);
    }
}