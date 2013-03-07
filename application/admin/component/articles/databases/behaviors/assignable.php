<?php
class ComArticlesDatabaseBehaviorAssignable extends KDatabaseBehaviorAbstract
{
    protected function _beforeTableUpdate(KCommandContext $context)
    {
        $data = $context->data;

        if($data->assign)
        {
            $attachment =  $this->getService('com://admin/attachments.model.attachments')
                                ->id($data->id)
                                ->getRow();

            $article =  $this->getService('com://admin/articles.model.articles')
                            ->id($attachment->relation->row)
                            ->getRow();

            if($article->image == $attachment->name)
            {
                // Toggle to remove the image
                $article->image = $article->thumbnail = null;
            }
            else
            {
                $article->image = $attachment->path;
                $article->thumbnail = $attachment->thumbnail->thumbnail;
            }

            $article->save();
        }

        return true;
    }
}