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
 * Pages Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesModelPages extends KModelTable
{
	protected $_page_xml;

	protected $_component_xml;

	protected $_system_xml;

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('enabled'	 , 'int')
			->insert('group_id'	 , 'int')
			->insert('menu'      , 'int')
			->insert('type'		 , 'cmd')
			->insert('home'      , 'int')
			->insert('trashed'	 , 'int');
	}
	
	protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->select('menu.id AS pages_menu_id')
              ->select('menu.menutype AS pages_menu_name')
              ->select('group.name AS group_name')
              ->select('component.name AS component_name')
              ->select('tbl.parent AS parent_id');
    }
    
    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
         parent::_buildQueryJoins($query);
         
         $query->join('LEFT', 'menu_types AS menu', 'tbl.menutype = menu.menutype')
               ->join('LEFT', 'components AS component', 'component.id = tbl.componentid AND tbl.type = "component"')
               ->join('LEFT', 'groups AS group', 'group.id = tbl.access');
    }

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);

		$state = $this->_state;

		if(is_numeric($state->home)) {
			$query->where('tbl.home','=', $state->home);
		}

		if(is_numeric($state->menu)) {
			$query->where('menu.id','=', $state->menu);
		}

		if(is_numeric($state->enabled)) {
			$query->where('tbl.published','=', $state->enabled);
		}

		//if(is_numeric($state->trashed)) {
		//	$query->where('tbl.trashed','=', $state->trashed);
		//}

		if(is_numeric($state->group_id)) {
			$query->where('tbl.group_id','=', $state->group_id);
		}

		if ($state->search)
		{
			$search = '%'.$state->search.'%';
			$query->where('tbl.title', 'LIKE',  $search);
		}
	}
	
	protected function _buildQueryOrder(KDatabaseQuery $query)
	{
		parent::_buildQueryOrder($query);

		$query->order(array('tbl.parent', 'tbl.ordering'));
	}
	
	public function getPageXml()
	{
		if(!isset($this->_page_xml))
		{
			switch($this->_state->type['name'])
			{
				case 'component':
					$path = JOOMLA_PATH.'/components/'.$this->_state->type['option'].'/views/'.
						$this->_state->type['view'].'/tmpl/'.$this->_state->type['layout'].'.xml';
					break;

				case 'url':
					$path = JPATH_BASE.'/administrator/components/com_pages/databases/rows/url.xml';
					break;

				case 'separator':
					$path = JPATH_BASE.'/administrator/components/com_pages/databases/rows/separator.xml';
					break;

				case 'menulink':
					$path = JPATH_BASE.'/administrator/components/com_pages/databases/rows/link.xml';
					break;
			}

			$xml = KFactory::tmp('lib.joomla.xml', array('simple'));
			$xml->loadFile($path);

			$this->_page_xml = $xml;
		}

		return $this->_page_xml;
	}

	public function getComponentXml()
	{
		if(!isset($this->_component_xml))
		{
			$path = JOOMLA_PATH.'/administrator/components/'.$this->_state->type['option'].'/config.xml';

			if(file_exists($path))
			{
				$xml = KFactory::tmp('lib.joomla.xml', array('simple'));
				$xml->loadFile($path);
			}
			else {
				$xml = '';
			}

			$this->_component_xml = $xml;
		}

		return $this->_component_xml;
	}

	public function getSystemXml()
	{
		if(!isset($this->_system_xml))
		{
			$path = JPATH_BASE.'/components/com_pages/databases/rows/component.xml';

			$xml = KFactory::tmp('lib.joomla.xml', array('simple'));
			$xml->loadFile($path);
			
			$this->_system_xml = $xml;
		}

		return $this->_system_xml;
	}

	public function getName()
	{
		$state = $this->getPageXml()->document->getElementByPath('state');

		if($state instanceof JSimpleXMLElement) {
			return $state->getElementByPath('name')->data();
		}
	}

	public function getDescription()
	{
		$state = $this->getPageXml()->document->getElementByPath('state');

		if($state instanceof JSimpleXMLElement) {
			return $state->getElementByPath('description')->data();
		}
	}

	public function getUrlParameters()
	{
		$page		= $this->getItem();
		$state		= $this->getPageXml()->document->getElementByPath('state');
		$parameters	= KFactory::tmp('admin::com.default.parameter.default');

		if($state instanceof JSimpleXMLElement)
		{
			$parameters->setXML($state->getElementByPath('url'));

			if($link = $page->link)
			{
				if(strpos($link, '&amp;') !== false) {
					$link = str_replace('&amp;', '&', $link);
				}

				parse_str($link, $link_parts);
				$parameters->loadArray($link_parts);
			}
		}

		return $parameters;
	}

	public function getStateParameters()
	{
		$page		= $this->getItem();
		$state		= $this->getPageXml()->document->getElementByPath('state');
		$parameters	=  KFactory::tmp('admin::com.default.parameter.default', array('data' => $page->params));

		if($state instanceof JSimpleXMLElement) {
			$parameters->setXML($state->getElementByPath('params'));
		}

		return $parameters;
	}

	public function getAdvancedParameters()
	{
		$page		= $this->getItem();
		$state		= $this->getPageXml()->document->getElementByPath('state');
		$parameters	=  KFactory::tmp('admin::com.default.parameter.default', array('data' => $page->params));

		if($state instanceof JSimpleXMLElement) {
			$parameters->setXML($state->getElementByPath('advanced'));
		}

		return $parameters;
	}

	public function getComponentParameters()
	{
		$page		= $this->getItem();
		$parameters	= KFactory::get('admin::com.default.parameter.default', array('data' => $page->params));

		if($xml = $this->getComponentXml())
		{
			// If hide is set, don't show the component configuration.
			$menu = $xml->document->attributes('menu');

			if(isset($menu) && $menu == 'hide') {
				return null;
			}

			// Don't show hidden elements.
			if (isset($xml->document->params[0]->param))
			{
				// Collect hidden elements.
				$hidden = array();

				for($i = 0, $n = count($xml->document->params[0]->param); $i < $n; $i++)
				{
					if($xml->document->params[0]->param[$i]->attributes('menu') == 'hide') {
						$hidden[] = $xml->document->params[0]->param[$i];
					}
					elseif($xml->document->params[0]->param[$i]->attributes('type') == 'radio'
						|| $xml->document->params[0]->param[$i]->attributes('type') == 'list')
					{
						$xml->document->params[0]->param[$i]->addAttribute('default', '');
						$xml->document->params[0]->param[$i]->addAttribute('type', 'list');
						$child = $xml->document->params[0]->param[$i]->addChild('option', array('value' => ''));
						$child->setData('Use Global');
					}
				}

				// Remove hidden elements.
				for($i = 0, $n = count($hidden); $i < $n; $i++) {
					$xml->document->params[0]->removeChild($hidden[$i]);
				}
			}

			$parameters->setXML($xml->document->params[0]);
		}

		return $parameters;
	}

	public function getSystemParameters()
	{
		$page		= $this->getItem();
		$xml		= $this->getSystemXml();
		$parameters = KFactory::tmp('admin::com.default.parameter.default', array('data' => $page->params));

		$parameters->setXML($xml->document->getElementByPath('state/params'));

		return $parameters;
	}

	public function getComponents()
	{
		$table = KFactory::get('admin::com.extensions.database.table.components');
		$query = $table->getDatabase()->getQuery()
			->where('link', '<>', '')
			->where('parent', '=', 0)
			->order('name');

		$components = $table->select($query);

		return $components;
	}

	public function getAssignedModules()
	{
		$query = $this->getTable()->getDatabase()->getQuery()
			->select('extensions_module_id')
			->where('pages_page_id', 'IN', array(0, $this->_state->id));

		$modules = KFactory::get('admin::com.pages.database.table.pages_modules_relations')
			->select($query, KDatabase::FETCH_FIELD_LIST);

		return $modules;
	}
}