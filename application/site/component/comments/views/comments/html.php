<?php

use Nooku\Framework;

class CommentsViewCommentsHtml extends BaseViewHtml
{
    public function render()
    {
        $this->user = JFactory::getUser();
        return parent::render();
    }
}