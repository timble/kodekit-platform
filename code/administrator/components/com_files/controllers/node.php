<?php

class ComFilesControllerNode extends ComDefaultControllerDefault
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'persistent' => false,
			'request' => array(
				'identifier' => 'files.files'
			)
		));

		parent::_initialize($config);
	}

	public function setRequest(array $request)
	{
		$config = KFactory::get('admin::com.files.database.row.config');
		$row = KFactory::tmp('admin::com.files.model.paths')->identifier($request['identifier'])->getItem();
		$config->setData(json_decode($row->parameters, true));

		return parent::setRequest($request);
	}
}
