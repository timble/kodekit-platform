<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateHelperListbox extends TemplateHelperSelect
{
    /**
     * Generates an HTML enabled listbox
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function enabled( $config = array())
    {
        $config = new ObjectConfig($config);
        $config->append(array(
            'name'      => 'enabled',
            'attribs'   => array(),
            'deselect'  => true,
            'prompt'    => '- Select -',
        ))->append(array(
            'selected'  => $config->{$config->name}
        ));

        $translator = $this->getObject('translator');

        $options = array();

        if($config->deselect) {
            $options[] = $this->option(array('label' => $translator($config->prompt), 'value' => ''));
        }

        $options[] = $this->option(array('label' => $translator( 'Enabled' ) , 'value' => 1 ));
        $options[] = $this->option(array('label' => $translator( 'Disabled' ), 'value' => 0 ));

        //Add the options to the config object
        $config->options = $options;

        return $this->optionlist($config);
    }

    /**
     * Generates an HTML published listbox
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function published( $config = array())
    {
        $config = new ObjectConfig($config);
        $config->append(array(
            'name'      => 'enabled',
            'attribs'   => array(),
            'deselect'  => true,
            'prompt'    => '- Select -',
        ))->append(array(
                'selected'  => $config->{$config->name}
        ));

        $translator = $this->getObject('translator');

        $options = array();

        if($config->deselect) {
            $options[] = $this->option(array('label' => $translator($config->prompt), 'value' => ''));
        }

        $options[] = $this->option(array('label' => $translator( 'Published' ) , 'value' => 1 ));
        $options[] = $this->option(array('label' => $translator( 'Draft' ), 'value' => 0 ));

        //Add the options to the config object
        $config->options = $options;

        return $this->optionlist($config);
    }

    /**
     * Generates an HTML limits listbox
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function limits($config = array())
    {
        $config = new ObjectConfig($config);
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

    /**
     * Generates an HTML timezones listbox
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function timezones($config = array())
    {
        $config = new ObjectConfig($config);
        $config->append(array(
            'name'		=> 'timezone',
            'attribs'	=> array(),
            'deselect'  => true,
            'prompt'    => '- '.$this->getObject('translator')->translate('Select Time Zone').' -',
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
            foreach ($locales as $locale) {
                $options[$group][] = $this->option(array('label' => $locale, 'value' => str_replace(' ', '_', $group.'/'.$locale)));
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

    /**
	 * Generates an HTML optionlist based on the distinct data from a model column.
	 *
	 * The column used will be defined by the name -> value => column options in cascading order.
	 *
	 * If no 'model' name is specified the model identifier will be created using the helper identifier. The model name
     * will be the pluralised package name.
	 *
	 * If no 'value' option is specified the 'name' option will be used instead. If no 'text'  option is specified the
     * 'value' option will be used instead.
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 * @see __call()
	 */
    protected function _render($config = array())
 	{
 	    $config = new ObjectConfig($config);
 	    $config->append(array(
 	        'autocomplete' => false
 	    ));

 	    if($config->autocomplete) {
 	        $result = $this->_autocomplete($config);
 	    } else {
 	        $result = $this->_listbox($config);
 	    }

 	    return $result;
 	}

	/**
	 * Generates an HTML optionlist based on the distinct data from a model column.
	 *
	 * The column used will be defined by the name -> value => column options in cascading order.
	 *
	 * If no 'model' name is specified the model identifier will be created using the helper identifier. The model name
     * will be the pluralised package name.
	 *
	 * If no 'value' option is specified the 'name' option will be used instead. If no 'text'  option is specified the
     * 'value' option will be used instead.
	 *
	 * @param 	array 	$cofnig An optional array with configuration options
	 * @return	string	Html
	 * @see __call()
	 */
	protected function _listbox($config = array())
 	{
		$config = new ObjectConfig($config);
		$config->append(array(
			'name'		  => '',
			'attribs'	  => array(),
			'model'		  => StringInflector::pluralize($this->getIdentifier()->package),
			'deselect'    => true,
		    'prompt'      => '- Select -',
		    'unique'	  => true
		))->append(array(
			'value'		 => $config->name,
			'selected'   => $config->{$config->name},
		    'identifier' => 'com:'.$this->getIdentifier()->package.'.model.'.StringInflector::pluralize($config->model)
		))->append(array(
			'label'		=> $config->value,
		))->append(array(
		    'filter' 	=> array('sort' => $config->label),
		));

		$list = $this->getObject($config->identifier)->setState(ObjectConfig::unbox($config->filter))->fetch();

		//Get the list of items
        $items = array();
        foreach($list as $key => $item) {
            $items[$key] = $item->getProperty($config->value);
        }

		if($config->unique) {
		    $items = array_unique($items);
		}

		//Compose the options array
        $options   = array();
 		if($config->deselect) {
         	$options[] = $this->option(array('label' => $this->getObject('translator')->translate($config->prompt)));
        }

 		foreach($items as $key => $value)
 		{
 		    $item      = $list->find($key);
 		    $options[] =  $this->option(array('label' => $item->{$config->label}, 'value' => $item->{$config->value}));
		}

        //Compose the selected array
        if($config->selected instanceof ModelEntityInterface)
        {
            $selected = array();
            foreach($config->selected as $entity) {
                $selected[] = $entity->{$config->value};
            }

            $config->selected = $selected;
        }

		//Add the options to the config object
		$config->options = $options;

		return $this->optionlist($config);
 	}

	/**
	 * Renders a listbox with autocomplete behavior
	 *
	 * @see    TemplateHelperBehavior::autocomplete
	 * @return string	The html output
	 */
	protected function _autocomplete($config = array())
 	{
		$config = new ObjectConfig($config);
		$config->append(array(
		    'name'		 => '',
			'attribs'	 => array(),
			'model'		 => StringInflector::pluralize($this->getIdentifier()->package),
			'validate'   => true,
		))->append(array(
		    'value'		 => $config->name,
		    'selected'   => $config->{$config->name},
			'identifier' => 'com:'.$this->getIdentifier()->package.'.model.'.StringInflector::pluralize($config->model)
		))->append(array(
			'label'		=> $config->value,
		))->append(array(
		    'filter' 	=> array('sort' => $config->label),
		));

        //For the autocomplete behavior
    	$config->element = $config->value;
    	$config->path    = $config->label;

        //Compose the selected array
        if($config->selected instanceof ModelEntityInterface)
        {
            $selected = array();
            foreach($config->selected as $entity) {
                $selected[] = $entity->{$config->value};
            }

            $config->selected = $selected;
        }

		$html = $this->getTemplate()->createHelper('behavior')->autocomplete($config);

	    return $html;
 	}

	/**
     * Search the mixin method map and call the method or trigger an error
     *
     * This function check to see if the method exists in the mixing map if not it will call the 'listbox' function.
     * The method name will become the 'name' in the config array.
     *
     * This can be used to auto-magically create select filters based on the function name.
     *
     * @param  string   $method     The function name
     * @param  array    $arguments  The function arguments
     * @throws \BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        if(!in_array($method, $this->getMethods()))
        {
            $config = $arguments[0];
            if(!isset($config['name'])) {
                $config['name']  = StringInflector::singularize(strtolower($method));
            }

            return $this->_render($config);
        }

        return parent::__call($method, $arguments);
    }
}