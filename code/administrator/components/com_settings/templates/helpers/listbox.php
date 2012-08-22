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
            'attribs'	=> array(),
            'deselect'  => true,
            'prompt'    => '- '.JText::_('Select Time Zone').' -',
        ));
        
        if ($config->deselect) {
            $options[] = $this->option(array('text' => $config->prompt, 'value' => ''));
        }
        
        foreach (DateTimeZone::listIdentifiers() as $identifier)
        {
            if (strpos($identifier, '/')) {
                list($group, $locale) = explode('/', $identifier, 2);
                $groups[$group][] = str_replace('_', ' ', $locale);
            }
        }
        
        $options[] = $this->option(array('text' => 'Coordinated Universal Time', 'value' => 'UTC'));
        foreach ($groups as $group => $locales) {
            $options[] = $this->option(array('text' => $group, 'group' => true));
            
            foreach ($locales as $locale) {
                $options[] = $this->option(array('text' => $locale, 'value' => str_replace(' ', '_', $group.'/'.$locale)));
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