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
 * Types Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesModelTypes extends KModelAbstract
{
	protected $_list;

	public function getList()
	{
		if(!isset($this->_list))
		{
			$table = KFactory::get('com://admin/extensions.database.table.components');
			$query = $table->getDatabase()->getQuery()
				->where('link', '<>', '')
				->where('parent', '=', 0)
				->order('name');

			$components = $table->select($query);

			// Iterate through the components.
			foreach($components as $component)
			{
				$path = JOOMLA_PATH.'/components/'.$component->option.'/views';

				if(!is_dir($path)) {
					continue;
				}

				$this->_list[$component->option] = new stdClass();
				$this->_list[$component->option]->id	= $component->id;
				$this->_list[$component->option]->title	= $component->name;
				$this->_list[$component->option]->name	= $component->option;

				// Iterator through the views.
				foreach(new DirectoryIterator($path) as $view)
				{
					$xml_path = $path.'/'.$view.'/metadata.xml';

					if(!$view->isDir() || substr($view, 0, 1) == '.' || !file_exists($xml_path)) {
						continue;
					}

					$xml = new DOMDocument();
					$xml->load($xml_path);

					$view_element = $xml->getElementsByTagName('view')->item(0);

					if($view_element->getAttribute('hidden') !== 'true')
					{
						$this->_list[$component->option]->views[(string) $view] = new stdClass();
						$this->_list[$component->option]->views[(string) $view]->name	= (string) $view;
						$this->_list[$component->option]->views[(string) $view]->title	= $view_element->getAttribute('title');

						// Iterate through the layouts.
						foreach(new DirectoryIterator($path.'/'.$view.'/tmpl') as $layout)
						{
							$pathinfo = pathinfo($layout);

							if(!$layout->isFile() || substr($layout, 0, 1) == '.' || $pathinfo['extension'] != 'xml') {
								continue;
							}

							$xml = new DOMDocument();
							$xml->load($path.'/'.$view.'/tmpl/'.$layout);

							$layout_element = $xml->getElementsByTagName('layout')->item(0);

							if(is_null($layout_element)) {
								continue;
							}

							if($layout_element->getAttribute('hidden') !== 'true')
							{
								$this->_list[$component->option]->views[(string) $view]->layouts[$pathinfo['filename']] = new stdClass();
								$this->_list[$component->option]->views[(string) $view]->layouts[$pathinfo['filename']]->name	= $pathinfo['filename'];
								$this->_list[$component->option]->views[(string) $view]->layouts[$pathinfo['filename']]->title	= $layout_element->getAttribute('title');

								$message_element = $layout_element->getElementsByTagName('message')->item(0);
								$this->_list[$component->option]->views[(string) $view]->layouts[$pathinfo['filename']]->description = trim($message_element->nodeValue);
							}
						}
					}
				}
			}
		}

		return $this->_list;
	}
}