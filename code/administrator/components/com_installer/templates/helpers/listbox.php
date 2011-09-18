<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Installer Template Listbox helper
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */
class ComInstallerTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    /**
     * Listbox for selecting application state in a column header
     *
     * @param  object  An optional KConfig object with configuration options.
     */
    public function application($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'      => 'application',
            'deselect'  => true,
            'selected'  => !is_array(KConfig::toData($config->application)) ? $config->application : '',
            'prompt'    => '- Select -'
        ));
        
        $options = array();
        if($config->deselect) {
            $options[] = $this->option(array('text' => JText::_($config->prompt), 'value' => false));
        }
        
        $options[] = $this->option(array('text' => JText::_('Administrator'), 'value' => 'administrator'));
        $options[] = $this->option(array('text' => JText::_('Site'), 'value' => 'site'));

        $config->options = $options;

        return $this->optionlist($config);
    }

    /**
     * Listbox for filtering plugins by type
     *
     * @param  object  An optional KConfig object with configuration options.
     */
    public function types($config = array())
 	{
 	    $config = new KConfig($config);
		$config->append(array(
			'model' 	=> 'plugins',
			'name'		=> 'type',
		));

		return $this->_listbox($config);
 	}
}