<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Extensions;

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function list_limits($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'		=> 'list_limit',
            'attribs'	=> array()
        ));

        $options[] 	= $this->option(array('value' => '5'));
        $options[] 	= $this->option(array('value' => '10'));
        $options[] 	= $this->option(array('value' => '15'));
        $options[] 	= $this->option(array('value' => '20'));
        $options[] 	= $this->option(array('value' => '25'));
        $options[] 	= $this->option(array('value' => '30'));
        $options[] 	= $this->option(array('value' => '50'));
        $options[] 	= $this->option(array('value' => '100'));

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
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'		=> 'cache_handler',
            'attribs'	=> array()
        ));

        jimport('joomla.cache.cache');
        $rows = JCache::getStores();

        foreach($rows as $row) {
            $options[] 	= $this->option(array('label' => $this->translate(ucfirst($row)), 'value' => $row));
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
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'		=> 'timezone',
            'attribs'	=> array(),
            'deselect'  => true,
            'prompt'    => '- '.$this->translate('Select Time Zone').' -',
        ));

        if ($config->deselect) {
            $options[] = $this->option(array('label' => $config->prompt, 'value' => ''));
        }

        foreach (\DateTimeZone::listIdentifiers() as $identifier)
        {
            if (strpos($identifier, '/'))
            {
                list($group, $locale) = explode('/', $identifier, 2);
                $groups[$group][] = str_replace('_', ' ', $locale);
            }
        }

        $options[] = $this->option(array('label' => 'Coordinated Universal Time', 'value' => 'UTC'));
        foreach ($groups as $group => $locales)
        {
            $options[] = $this->option(array('label' => $group, 'group' => true));

            foreach ($locales as $locale) {
                $options[] = $this->option(array('label' => $locale, 'value' => str_replace(' ', '_', $group.'/'.$locale)));
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
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'		=> 'error_reporting',
            'attribs'	=> array()
        ));

        $options[] 	= $this->option(array('label' => 'Server Defaults', 'value' => 0));
        $options[] 	= $this->option(array('label' => 'Development', 'value' => 1));
        $options[] 	= $this->option(array('label' => 'Production' , 'value' => 2));

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
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'		=> 'mailer',
            'attribs'	=> array()
        ));

        $options[] 	= $this->option(array('label' => 'PHP mail function', 'value' => 'mail'));
        $options[] 	= $this->option(array('label' => 'Sendmail', 'value' =>  'sendmail'));
        $options[] 	= $this->option(array('label' => 'SMTP Server', 'value' => 'smtp'));

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
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'		=> 'smtpsecure',
            'attribs'	=> array()
        ));

        $options[] 	= $this->option(array('label' => 'None', 'value' => 'none'));
        $options[] 	= $this->option(array('label' => 'SSL', 'value' =>  'ssl'));
        $options[] 	= $this->option(array('label' => 'TLS', 'value' => 'tls'));

        $list = $this->optionlist(array(
            'options'   => $options,
            'name'      => $config->name,
            'selected'  => $config->selected,
            'attribs'   => $config->attribs
        ));

        return $list;
    }
}