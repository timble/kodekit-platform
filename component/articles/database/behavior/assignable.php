<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Articles;

use Nooku\Library;

class DatabaseBehaviorAssignable extends Library\DatabaseBehaviorAbstract
{
    protected function _beforeTableUpdate(Library\CommandContext $context)
    {
        $data = $context->data;

        if($data->assign)
        {
            $attachment =  $this->getObject('com:attachments.model.attachments')
                ->id($data->id)
                ->getRow();

            $article =  $this->getObject('com:articles.model.articles')
                ->id($attachment->relation->row)
                ->getRow();

            // Toggle to remove the image
            if($article->image != $attachment->path)
            {
                $article->image = $attachment->path;
                $article->thumbnail = $attachment->thumbnail->thumbnail;   
            }
            else $article->image = $article->thumbnail = null;

            $article->save();
        }

        return true;
    }
}