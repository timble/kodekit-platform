<?php

class ComFilesCommandAuthorize extends ComDefaultCommandAuthorize
{
	protected static $_group_map = array(
		4 => 18,
		3 => 19,
		2 => 20,
		1 => 21
	);

	protected function _authorize(KCommandContext $context)
	{
		$minimum = KFactory::get('admin::com.files.database.row.config')->allowed_media_usergroup;
		$minimum = isset(self::$_group_map[$minimum]) ? self::$_group_map[$minimum] : 18;

		$result = KFactory::get('lib.joomla.user')->get('gid') >= $minimum;

		return $result;
	}

    public function _controllerBeforeAdd(KCommandContext $context)
    {
		return $this->_authorize($context);
	}

	public function _controllerBeforeEdit(KCommandContext $context)
    {
		return $this->_authorize($context);
	}

	public function _controllerBeforeDelete(KCommandContext $context)
    {
		return $this->_authorize($context);
	}
}