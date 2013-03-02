<?php
/**
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Compponents Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */

class ComLanguagesViewComponentsHtml extends ComDefaultViewHtml
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'auto_assign' => false
        ));
        
        parent::_initialize($config);
    }
    
    public function render()
    {
        $tables     = $this->getService('com://admin/languages.model.tables')->getRowset();
        $components = $this->getService('com://admin/extensions.model.components')
            ->id(array_unique($tables->extensions_component_id))
            ->getRowset();
        
        foreach($tables as $table) {
            $components->find($table->extensions_component_id)->enabled = $table->enabled;
        }
        
        $this->components = $components;
        $this->total      = count($components);
        
        return parent::render();
    }
}