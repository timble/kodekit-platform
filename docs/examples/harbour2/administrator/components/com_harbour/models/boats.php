<?php
class HarbourModelBoats extends KModelTable
{
	public function getList()
	{
		$list = parent::getList(); 
		foreach($list as $item) {
			$item->link = 'index.php?option=com_harbour&view=boat&id='.$item->id;
		}
		return $list;
	}
}
