<?php

use Nooku\Framework;

class ComCommentsViewCommentsHtml extends ComDefaultViewHtml
{
	public function render()
	{
		$this->user = JFactory::getUser();
		return parent::render();
	}
}
