<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
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
       
		$editors = KFactory::get('admin::com.extensions.model.plugins')
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
	
	public function offsets($config = array())
	{
		$config = new KConfig($config);
        $config->append(array(
            'name'		=> 'offset',
            'attribs'	=> array()
        ));
        
        $options[] 	= $this->option(array('text' => '(UTC -12:00) International Date Line West', 'value' => -12));
		$options[] 	= $this->option(array('text' => '(UTC -11:00) Midway Island, Samoa', 'value' => -11));
		$options[] 	= $this->option(array('text' => '(UTC -10:00) Hawaii', 'value' => -10));
		$options[] 	= $this->option(array('text' => '(UTC -09:30) Taiohae, Marquesas Islands', 'value' => -9.5));
		$options[] 	= $this->option(array('text' => '(UTC -09:00) Alaska', 'value' => -9));
		$options[] 	= $this->option(array('text' => '(UTC -08:00) Pacific Time (US &amp; Canada)', 'value' => -8));
		$options[] 	= $this->option(array('text' => '(UTC -07:00) Mountain Time (US &amp; Canada)', 'value' => -7));
		$options[] 	= $this->option(array('text' => '(UTC -06:00) Central Time (US &amp; Canada), Mexico City', 'value' => -6));
		$options[] 	= $this->option(array('text' => '(UTC -05:00) Eastern Time (US &amp; Canada), Bogota, Lima', 'value' => -5));
		$options[] 	= $this->option(array('text' => '(UTC -04:30) Venezuela', 'value' => -4.5));
		$options[] 	= $this->option(array('text' => '(UTC -04:00) Atlantic Time (Canada), Caracas, La Paz', 'value' => -4));
		$options[] 	= $this->option(array('text' => '(UTC -03:30) St. John\'s, Newfoundland, Labrador', 'value' => -3.5));
		$options[] 	= $this->option(array('text' => '(UTC -03:00) Brazil, Buenos Aires, Georgetown', 'value' => -3));
		$options[] 	= $this->option(array('text' => '(UTC -02:00) Mid-Atlantic', 'value' => -2));
		$options[] 	= $this->option(array('text' => '(UTC -01:00) Azores, Cape Verde Islands', 'value' => -1));
		$options[] 	= $this->option(array('text' => '(UTC 00:00) Western Europe Time, London, Lisbon, Casablanca', 'value' => 0));
		$options[] 	= $this->option(array('text' => '(UTC +01:00) Amsterdam, Berlin, Brussels, Copenhagen, Madrid, Paris', 'value' => 1));
		$options[] 	= $this->option(array('text' => '(UTC +02:00) Istanbul, Jerusalem, Kaliningrad, South Africa', 'value' => 2));
		$options[] 	= $this->option(array('text' => '(UTC +03:00) Baghdad, Riyadh, Moscow, St. Petersburg', 'value' => 3));
		$options[] 	= $this->option(array('text' => '(UTC +03:30) Tehran', 'value' => 3.5));
		$options[] 	= $this->option(array('text' => '(UTC +04:00) Abu Dhabi, Muscat, Baku, Tbilisi', 'value' => 4));
		$options[] 	= $this->option(array('text' => '(UTC +04:30) Kabul', 'value' => 4.5));
		$options[] 	= $this->option(array('text' => '(UTC +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent', 'value' => 5));
		$options[] 	= $this->option(array('text' => '(UTC +05:30) Bombay, Calcutta, Madras, New Delhi, Colombo', 'value' => 5.5));
		$options[] 	= $this->option(array('text' => '(UTC +05:45) Kathmandu', 'value' => 5.75));
		$options[] 	= $this->option(array('text' => '(UTC +06:00) Almaty, Dhaka', 'value' => 6));
		$options[] 	= $this->option(array('text' => '(UTC +06:30) Yagoon', 'value' => 6.5));
		$options[] 	= $this->option(array('text' => '(UTC +07:00) Bangkok, Hanoi, Jakarta', 'value' => 7));
		$options[] 	= $this->option(array('text' => '(UTC +08:00) Beijing, Perth, Singapore, Hong Kong', 'value' => 8));
		$options[] 	= $this->option(array('text' => '(UTC +08:00) Ulaanbaatar, Western Australia', 'value' => 8.75));
		$options[] 	= $this->option(array('text' => '(UTC +09:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk', 'value' => 9));
		$options[] 	= $this->option(array('text' => '(UTC +09:30) Adelaide, Darwin, Yakutsk', 'value' => 9.5));
		$options[] 	= $this->option(array('text' => '(UTC +10:00) Eastern Australia, Guam, Vladivostok', 'value' => 10));
		$options[] 	= $this->option(array('text' => '(UTC +10:30) Lord Howe Island (Australia)', 'value' => 10.5));
		$options[] 	= $this->option(array('text' => '(UTC +11:00) Magadan, Solomon Islands, New Caledonia', 'value' => 11));
		$options[] 	= $this->option(array('text' => '(UTC +11:30) Norfolk Island', 'value' => 11.5));
		$options[] 	= $this->option(array('text' => '(UTC +12:00) Auckland, Wellington, Fiji, Kamchatka', 'value' => 12));
		$options[] 	= $this->option(array('text' => '(UTC +12:45) Chatham Island', 'value' => 12.75));
		$options[] 	= $this->option(array('text' => '(UTC +13:00) Tonga', 'value' => 13));
		$options[] 	= $this->option(array('text' => '(UTC +14:00) Kiribati', 'value' => 14));

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

        $options[] 	= $this->option(array('text' => 'System Default', 'value' => -1));
		$options[] 	= $this->option(array('text' => 'None', 'value' => 0));
		$options[] 	= $this->option(array('text' => 'Simple', 'value' =>  E_ERROR | E_WARNING | E_PARSE));
		$options[] 	= $this->option(array('text' => 'Maximum', 'value' => E_ALL));

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