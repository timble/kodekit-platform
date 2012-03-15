<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 */

class ComSettingsTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
	public function editors($config = array())
	{
		$config = new KConfig($config);
        $config->append(array(
            'name'		=> 'editor'
        ));
       
		$editors = $this->getService('com://admin/extensions.model.plugins')
		            ->type('editors')
		            ->enabled(1)
		            ->sort(array('ordering', 'name'))
		            ->getList();
	
		foreach($editors as $editor) {
			$options[] 	= $this->option(array('text' => JText::_($editor->title), 'value' => $editor->name));
		}

		$list = $this->optionlist(array(
			'options'   => $options,
			'name'      => $config->name,
			'selected'  => $config->selected,
			'attribs'   => $config->attribs
		));

		return $list;
	}

	public function list_limits($config = array())
	{
		$config = new KConfig($config);
        $config->append(array(
            'name'		=> 'list_limit',
            'attribs'	=> array()
        ));

		$options[] 	= $this->option(array('text' => '5', 'value' => '5'));
		$options[] 	= $this->option(array('text' => '10', 'value' => '10'));
		$options[] 	= $this->option(array('text' => '15', 'value' => '15'));
		$options[] 	= $this->option(array('text' => '20', 'value' => '20'));
		$options[] 	= $this->option(array('text' => '25', 'value' => '25'));
		$options[] 	= $this->option(array('text' => '30', 'value' => '30'));
		$options[] 	= $this->option(array('text' => '50', 'value' => '50'));
		$options[] 	= $this->option(array('text' => '100', 'value' => '100'));

		$list = $this->optionlist(array(
			'options'   => $options,
			'name'      => $config->name,
			'selected'  => $config->selected,
			'attribs'   => $config->attribs
		));

		return $list;
	}
	
	public function cache_handlers($config = array())
	{
		$config = new KConfig($config);
        $config->append(array(
            'name'		=> 'cache_handler',
            'attribs'	=> array()
        ));
        
        jimport('joomla.cache.cache');
		$rows = JCache::getStores();

		foreach($rows as $row) {
			$options[] 	= $this->option(array('text' => JText::_(ucfirst($row)), 'value' => $row));
		}

		$list = $this->optionlist(array(
			'options'   => $options,
			'name'      => $config->name,
			'selected'  => $config->selected,
			'attribs'   => $config->attribs
		));

		return $list;
	}
	
    public function timezones($config = array())
	{
		$config = new KConfig($config);
        $config->append(array(
            'name'		=> 'timezone',
            'attribs'	=> array()
        ));
        
        foreach (DateTimeZone::listIdentifiers() as $identifier)
        {
            if (strpos($identifier, '/')) {
                list($group, $locale) = explode('/', $identifier, 2);
                $groups[$group][] = str_replace('_', ' ', $locale);
            }
        }
        
        $options[] = $this->option(array('text' => 'Universal Time, Coordinated (UTC)', 'value' => 'UTC'));
        foreach ($groups as $group => $locales) {
            $options[] = $this->option(array('text' => $group, 'disable' => true));
            
            foreach ($locales as $locale) {
                $options[] = $this->option(array('text' => '&nbsp;&nbsp;&nbsp;&nbsp;'.$locale, 'value' => $locale));
            }
        }

		$list = $this->optionlist(array(
			'options'   => $options,
			'name'      => $config->name,
			'selected'  => $config->selected,
			'attribs'   => $config->attribs
		));

		return $list;
	}
	
	public function session_handlers($config = array())
	{
		$config = new KConfig($config);
        $config->append(array(
            'name'		=> 'session_handler',
            'attribs'	=> array()
        ));

        $rows = JSession::getStores();

		foreach($rows as $row) {
			$options[] 	= $this->option(array('text' => JText::_(ucfirst($row)), 'value' => $row));
		}

		$list = $this->optionlist(array(
			'options'   => $options,
			'name'      => $config->name,
			'selected'  => $config->selected,
			'attribs'   => $config->attribs
		));

		return $list;
	}
	
	public function error_reportings($config = array())
	{
		$config = new KConfig($config);
        $config->append(array(
            'name'		=> 'error_reporting',
            'attribs'	=> array()
        ));

        $options[] 	= $this->option(array('text' => 'Server Defaults', 'value' => 0));
		$options[] 	= $this->option(array('text' => 'Development', 'value' => 1));
		$options[] 	= $this->option(array('text' => 'Production' , 'value' => 2));

		$list = $this->optionlist(array(
			'options'   => $options,
			'name'      => $config->name,
			'selected'  => $config->selected,
			'attribs'   => $config->attribs
		));

		return $list;
	}
	
	public function force_ssl($config = array())
	{
		$config = new KConfig($config);
        $config->append(array(
            'name'		=> 'force_ssl',
            'attribs'	=> array()
        ));

		$options[] 	= $this->option(array('text' => 'None', 'value' => 0));
		$options[] 	= $this->option(array('text' => 'Administrator Only', 'value' =>  1));
		$options[] 	= $this->option(array('text' => 'Entire Site', 'value' => 2));

		$list = $this->optionlist(array(
			'options'   => $options,
			'name'      => $config->name,
			'selected'  => $config->selected,
			'attribs'   => $config->attribs
		));

		return $list;
	}
	
	public function mailers($config = array())
	{
		$config = new KConfig($config);
        $config->append(array(
            'name'		=> 'mailer',
            'attribs'	=> array()
        ));

		$options[] 	= $this->option(array('text' => 'PHP mail function', 'value' => 'mail'));
		$options[] 	= $this->option(array('text' => 'Sendmail', 'value' =>  'sendmail'));
		$options[] 	= $this->option(array('text' => 'SMTP Server', 'value' => 'smtp'));

		$list = $this->optionlist(array(
			'options'   => $options,
			'name'      => $config->name,
			'selected'  => $config->selected,
			'attribs'   => $config->attribs
		));

		return $list;
	}
	
	public function smtpsecure($config = array())
	{
		$config = new KConfig($config);
        $config->append(array(
            'name'		=> 'smtpsecure',
            'attribs'	=> array()
        ));

		$options[] 	= $this->option(array('text' => 'None', 'value' => 'none'));
		$options[] 	= $this->option(array('text' => 'SSL', 'value' =>  'ssl'));
		$options[] 	= $this->option(array('text' => 'TLS', 'value' => 'tls'));

		$list = $this->optionlist(array(
			'options'   => $options,
			'name'      => $config->name,
			'selected'  => $config->selected,
			'attribs'   => $config->attribs
		));

		return $list;
	}
}