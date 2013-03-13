<?php

use Nooku\Framework;

class CommentsViewCommentsHtml extends Framework\ViewHtml
{
    public function render()
    {
        $this->user = JFactory::getUser();
        return parent::render();
    }
}