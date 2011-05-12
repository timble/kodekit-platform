<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Languages Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 */

jimport('joomla.filesystem.folder');

class ComLanguagesModelLanguages extends KModelAbstract
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('limit'    , 'int', 0)
			->insert('offset'   , 'int', 0)
			->insert('direction', 'word', 'asc')
			->insert('application', 'cmd', 'site')

			->insert('default'	, 'boolean', false, true)
			->insert('language'	, 'admin::com.languages.filter.iso', null, true);
	}

	public function getItem()
	{
		if (!isset($this->_item))
		{
			if($this->_state->isUnique())
            {
            	$state = $this->_state;
            	$app = $state->application == 'admin' ? 'administrator' : $state->application;

				//Get application information
				$client	= JApplicationHelper::getClientInfo($app, true);
            	if (empty($client)) {
					throw new KModelException('Invalid client');
				}

				$default = JComponentHelper::getParams('com_languages')->get($app, 'en-GB');
				$path = JLanguage::getLanguagePath($client->path);

				if ($state->default) {
					$lang = $default;
				}
				else if ($state->language) {
					$lang = is_array($state->language) ? $state->language[0] : $state->language;
				}
				$path .= '/'.$lang.'/'.$lang.'.xml';

				$row = KFactory::tmp('admin::com.languages.database.row.language', array('data' => array(
					'manifest_file' => $path,
					'client' => $client
				)));
				$row->default = $row->language == $default;

				$this->_item = $row;
            }

		}

		return parent::getItem();
	}

	public function getList()
	{
		if (!isset($this->_list))
		{
			$state = $this->_state;
            $app = $state->application == 'admin' ? 'administrator' : $state->application;

			//Get application information
			$client	= JApplicationHelper::getClientInfo($app, true);

			if (empty($client)) {
				throw new KModelException('Invalid client');
			}

			$path    = JLanguage::getLanguagePath($client->path);
			$default = JComponentHelper::getParams('com_languages')->get($app, 'en-GB');

			if ($state->language)
			{
				$lang = is_array($state->language) ? $state->language[0] : $state->language;
				$path .= '/'.$lang;
			}

			if (!JFolder::exists($path)) {
				throw new KModelException('Client path is not a valid folder');
			}

			$files = JFolder::files($path, '^([-_A-Za-z]*)\.xml$', true, true);

			//Set the total
			$this->_total = count($files);

			//Apply limit and offset
			$files = array_slice($files, $state->offset, $state->limit ? $state->limit : $this->_total);

			if (strtolower($this->_state->direction) == 'desc') {
				$files = array_reverse($files);
			}

			$rowset = KFactory::tmp('admin::com.languages.database.rowset.languages');
			foreach ($files as $file)
			{
				$row = KFactory::tmp('admin::com.languages.database.row.language', array('data' => array(
					'manifest_file' => $file,
					'client' => $client
				)));
				$row->default = $row->language == $default;

				$rowset->insert($row);
			}

			$this->_list = $rowset;
		}

		return parent::getList();
	}

	public function getTotal()
	{
		if (!$this->_total) {
			$this->getList();
		}

		return $this->_total;
	}

	public function getColumn($column)
	{
		return $this->getList();
	}
}