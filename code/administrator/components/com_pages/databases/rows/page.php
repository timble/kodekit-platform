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
 * Page Database Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseRowPage extends ComPagesDatabaseRowNode implements KObjectInstantiatable
{
	public static function getInstance(KConfig $config, KFactoryInterface $factory)
	{
		if($config->data['type']) {
			$type = $config->data['type'] == 'menulink' ? 'Link' : ucfirst($config->data['type']);
		} else {
			$type = 'Page';
		}
	
		$class_name	= 'ComPagesDatabaseRow'.$type;
		$instance	= new $class_name($config);

		return $instance;
	}

	public function save()
	{
		// Load the old row if editing an existing page.
		if(!$this->_new)
		{
			$old_row = KFactory::get('com://admin/pages.database.table.pages')
				->select($this->id, KDatabase::FETCH_ROW);
		}

		// Set menu name.
		if($this->pages_menu_id)
		{
			$menu = KFactory::get('com://admin/pages.database.table.menus')
				->select($this->pages_menu_id, KDatabase::FETCH_ROW);

			if($this->_new || $menu->name != $old_row->pages_menu_name) {
				$this->pages_menu_name = $menu->name;
			}
		}

		// Set component id.
		if($this->type == 'component' && $this->type_option)
		{
			$component = KFactory::get('com://admin/extensions.model.components')
				->set('option', $this->type_option)
				->getItem();

			if($this->_new || $component->id != $old_row->component_id) {
				$this->component_id = $component->id;
			}
		}

		if($this->type != 'component') {
			$this->component_id = 0;
		}

		// Set link.
		if($this->type == 'component' && $this->type_option && $this->type_view) {
			$link  = 'index.php?option='.$this->type_option.'&view='.$this->type_view;

			if($this->type_layout && $this->type_layout != 'default') {
				$link .= '&layout='.$this->type_layout;
			}

			if($this->urlparams) {
				foreach($this->urlparams as $key => $value) {
					$link .= '&'.$key.'='.$value;
				}
			}

			if($this->_new || $link != $old_row->link) {
				$this->link = $link;
			}
		}
		elseif($this->type == 'menulink') {
			$this->link = 'index.php?Itemid='.$this->params['menu_item'];
		}

		// Set level.
		if(isset($this->_modified['parent_id']))
		{
				$parent = KFactory::get('com://admin/pages.database.table.pages')
					->select($this->parent_id, KDatabase::FETCH_ROW);

				$this->level = $parent->level + 1;
		}

		// Fix level of subpages.
		if(isset($this->_modified['parent_id']) && !$this->_new)
		{
			$level		= $this->level;
			$subpages	= KFactory::get('com://admin/pages.database.table.pages')
				->select(array('parent_id' => $this->id));

			while(count($subpages))
			{
				$subpages->level = ++$level;
				$subpages->save();

				$query = KFactory::get('koowa:database.query')
					->where('parent_id', 'IN', $subpages->id);

				$subpages = KFactory::get('com://admin/pages.database.table.pages')
					->select($query);
			}
		}

		if(isset($this->_modified['home']) && $this->home == 1)
		{
			$page = KFactory::get('com://admin/pages.database.table.pages')
				->select(array('home' => 1), KDatabase::FETCH_ROW);

			$page->home = 0;
			$page->save();
		}

		// Set parameters.
		if(isset($this->_modified['params']))
		{
			$params	= KFactory::get('com://admin/default.parameter.default');
			$params->bind($this->_data['params']);

			$this->params = $params->toString();
		}

		return parent::save();
	}

	public function delete()
	{
		// Delete subpages.
		$subpages	= KFactory::get('com://admin/pages.database.table.pages')
			->select(array('parent_id' => $this->id));

		while(count($subpages))
		{
			$subpages->delete();

			$query = KFactory::get('koowa:database.query')
				->where('parent_id', 'IN', $subpages->id);

			$subpages = KFactory::get('com://admin/pages.database.table.pages')
				->select($query);
		}

		return parent::delete();
	}
}