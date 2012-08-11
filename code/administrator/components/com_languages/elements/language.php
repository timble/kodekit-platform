<?php

class JElementLanguage extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	public $_name = 'Language';

	public function fetchElement($name, $value, &$node, $control_name)
	{
		$db = KFactory::get('lib.joomla.database');

		KViewHelper::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_nooku'.DS.'helpers');
		return KViewHelper::_('nooku.select.languages', $value, $control_name.'['.$name.']', array('class' => 'inputbox', 'size' => '1'), null, false);
	}
}