<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Select Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateHelperSelect extends TemplateHelperAbstract
{
	/**
	 * Generates a select option
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return	object	A stdClass containing the option attributes
	 */
	public function option( $config = array() )
	{
		$config = new ObjectConfig($config);
		$config->append(array(
            'id'        => null,
            'name'   	=> 'id',
			'value' 	=> null,
			'label'   	=> '',
			'disabled'	=> false,
            'level'     => 1,
			'attribs'	=> array(),
		));

		$option = new \stdClass;
        $option->id       = $config->id;
        $option->name     = $config->name;
		$option->value 	  = $config->value;
		$option->label    = trim( $config->label ) ? $config->label : $config->value;
		$option->disabled = $config->disabled;
		$option->level    = $config->level;
		$option->attribs  = ObjectConfig::unbox($config->attribs);

        if($config->level) {
            $option->attribs['class'] = array('level'.$config->level);
        }

        if($config->disabled) {
            $option->attribs['class'] = array('disabled');
        }

		return $option;
	}

    /**
     * Generates a select option list
     *
     * @param 	array 	$config An optional array with configuration options
     * @return	array	An array of objects containing the option attributes
     */
    public function options( $config = array() )
    {
        $config = new ObjectConfig($config);
        $config->append(array(
            'entity'    => array(),
            'name'   	=> 'id',
            'value' 	=> 'id',
            'label'   	=> 'id',
            'disabled'  => null,
            'attribs'	=> array(),
        ));

        $options = array();
        foreach($config->entity as $entity)
        {
            $option = array(
                'id'       => isset($entity->{$config->name}) ? $entity->{$config->name} : null,
                'name'     => $config->name,
                'disabled' => $config->disabled,
                'attribs'  => ObjectConfig::unbox($config->attribs),
                'value'    => $entity->{$config->value},
                'label'    => $entity->{$config->label},
            );

            if($config->entity instanceof \RecursiveIteratorIterator) {
                $option['level'] = $config->entity->getDepth() + 1;
            }

            $options[] = $this->option($option);
        }

        return $options;
    }

	/**
	 * Generates an HTML select list
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 */
	public function optionlist($config = array())
	{
		$config = new ObjectConfig($config);
		$config->append(array(
			'options' 	=> array(),
			'name'   	=> 'id',
			'selected'	=> null,
            'disabled'  => null,
			'translate'	=> false,
            'attribs'	=> array('size' => 1),
		));

		$attribs = $this->buildAttributes($config->attribs);

		$html = array();
		$html[] = '<select name="'. $config->name .'" '. $attribs .'>';

		foreach($config->options as $option)
		{
            $value = $option->value;
			$label  = $config->translate ? $this->translate( $option->label ) : $option->label;

			$extra = '';
			if(isset($option->disabled) && $option->disabled) {
				$extra .= 'disabled="disabled"';
			}

			if(isset($option->attribs)) {
				$attribs = $this->buildAttributes($option->attribs);;
			}

			if(!is_null($config->selected))
			{
				if ($config->selected instanceof ObjectConfig)
				{
					foreach ($config->selected as $selected)
					{
						$sel = is_object( $selected ) ? $selected->value : $selected;
						if ((string) $value == (string) $sel)
						{
							$extra .= 'selected="selected"';
							break;
						}
					}
				}
				else $extra .= ((string) $value == (string) $config->selected ? ' selected="selected"' : '');
			}

			$html[] = '<option value="'. $value .'" '.$extra.' '.$attribs.'>' . $label . '</option>';
		}

		$html[] = '</select>';

		return implode(PHP_EOL, $html);
	}

	/**
	 * Generates an HTML radio list
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 */
	public function radiolist( $config = array())
	{
		$config = new ObjectConfig($config);
		$config->append(array(
			'options' 	=> array(),
            'legend'    => null,
            'name'   	=> 'id',
			'selected'	=> null,
			'translate'	=> false,
            'attribs'	=> array(),
		));

		$attribs = $this->buildAttributes($config->attribs);

		$html   = array();
        $html[] = '<fieldset  name="'. $config->name .'" '. $attribs .'>';

        if(isset($config->legend)) {
            $html[] = '<legend>'.$config->translate ? $this->translate( $config->legend ) : $config->legend.'</legend>';
        }

		foreach($config->options as $option)
		{
            $value = $option->value;
            $label = $config->translate ? $this->translate( $option->label ) : $option->label;

            $extra = '';
            $extra = ($value == $config->selected ? 'checked="checked"' : '');

            if(isset($option->disabled) && $option->disabled) {
                $extra .= 'disabled="disabled"';
            }

            if(isset($option->attribs)) {
                $attribs = $this->buildAttributes($option->attribs);
            }

            $html[] = '<label class="radio" for="'.$config->name.$option->id.'">';
            $html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.$option->id.'" value="'.$value.'" '.$extra.' '.$attribs.' />';
            $html[] = $label;
            $html[] = '</label>';
		}

        $html[] = '</fieldset>';

		return implode(PHP_EOL, $html);
	}

	/**
	 * Generates an HTML check list
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 */
	public function checklist( $config = array())
	{
		$config = new ObjectConfig($config);
		$config->append(array(
			'options' 	=> array(),
            'legend'    => null,
            'name'   	=> 'id',
			'selected'	=> null,
			'translate'	=> false,
            'attribs'	=> array(),
		));

		$attribs = $this->buildAttributes($config->attribs);

		$html = array();

        $html[] = '<fieldset  name="'. $config->name .'" '. $attribs .'>';

        if(isset($config->legend)) {
            $html[] = '<legend>'.$config->translate ? $this->translate( $config->legend ) : $config->legend.'</legend>';
        }

		foreach($config->options as $option)
		{
			$value = $option->value;
			$label = $config->translate ? $this->translate( $option->label ) : $option->label;

			$extra = '';

			if ($config->selected instanceof ObjectConfig)
			{
				foreach ($config->selected as $value)
				{
					$sel = is_object( $value ) ? $value->{$config->value} : $value;
					if ($value == $sel)
					{
						$extra .= 'checked="checked"';
						break;
					}
				}
			}
			else $extra .= ($value == $config->selected) ? 'checked="checked"' : '';

            if(isset($option->disabled) && $option->disabled) {
                $extra .= 'disabled="disabled"';
            }

            if(isset($option->attribs)) {
                $attribs = $this->buildAttributes($option->attribs);
            }

			$html[] = '<label class="checkbox" for="'.$option->name.$option->id.'">';
			$html[] = '<input type="checkbox" name="'.$option->name.'[]" id="'.$option->name.$option->id.'" value="'.$value.'" '.$extra.' '.$attribs.' />';
			$html[] = $label;
			$html[] = '</label>';
		}

        $html[] = '</fieldset>';

		return implode(PHP_EOL, $html);
	}

	/**
	 * Generates an HTML boolean radio list
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 */
	public function booleanlist( $config = array())
	{
		$config = new ObjectConfig($config);
		$config->append(array(
			'name'   	=> '',
			'true'		=> 'yes',
			'false'		=> 'no',
			'selected'	=> null,
			'translate'	=> true,
            'attribs'	=> array(),
		));

		$name    = $config->name;
		$attribs = $this->buildAttributes($config->attribs);

		$html  = array();

		$extra = !$config->selected ? 'checked="checked"' : '';
		$label = $config->translate ? $this->translate( $config->false ) : $config->false;

		$html[] = '<label class="radio" for="'.$name.'0">';
		$html[] = '<input type="radio" name="'.$name.'" id="'.$name.'0" value="0" '.$extra.' '.$attribs.' />';
		$html[] = $label;
		$html[] = '</label>';

		$extra = $config->selected ? 'checked="checked"' : '';
		$label = $config->translate ? $this->translate( $config->true ) : $config->true;

		$html[] = '<label class="radio" for="'.$name.'1">';
		$html[] = '<input type="radio" name="'.$name.'" id="'.$name.'1" value="1" '.$extra.' '.$attribs.' />';
		$html[] = $label;
		$html[] = '</label>';

		return implode(PHP_EOL, $html);
	}
}
