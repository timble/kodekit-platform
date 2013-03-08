<?php

use Nooku\Framework;

class ComCommentsViewCommentsHtml extends ComBaseViewHtml
{
	public function render()
	{
		$this->user = JFactory::getUser();
		return parent::render();
	}
}
