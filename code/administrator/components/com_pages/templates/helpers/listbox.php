<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
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
 * @subpackage  Pages
 */

class ComPagesTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
	public function parents($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'name'	=> 'parent_id'
		));

		$query = KFactory::get('koowa:database.query')
			->where('pages_menu_id', '=', $config->pages_menu_id)
			->where('enabled', '<>', -2)
			->order(array('parent_id', 'ordering'))
			->limit(0);

		$pages = KFactory::get('com://admin/pages.database.table.pages')
			->select($query);

		if($config->pages_page_id) 
		{
			if($row = $pages->find($config->pages_page_id)) {
				$pages->removeRow($row);
			}
		}

		$html		= array();
		$selected	= $config->selected == 0 ? 'checked="checked"' : '';

		$html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.'0" value="0" '.$selected.' />';
		$html[] = '<label for="'.$config->name.'0">'.JText::_('Top').'</label>';
		$html[] = '<br />';

		$iterator	= new RecursiveIteratorIterator($pages, RecursiveIteratorIterator::SELF_FIRST);

		foreach($iterator as $page)
		{
			$selected	= $config->selected == $page->id ? 'checked="checked"' : '';

			if($iterator->getDepth() + 1) {
				$html[] = str_repeat('.&nbsp;&nbsp;&nbsp;&nbsp;', $iterator->getDepth());
				$html[] = '<sup>|_</sup>&nbsp;';
			}

			$html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.$page->id.'" value="'.$page->id.'" '.$selected.' />';
			$html[] = '<label for="'.$config->name.$page->id.'">'.$page->title.'</label>';
			$html[] = '<br />';
		}

		return implode(PHP_EOL, $html);
	}
}