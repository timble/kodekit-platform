<?php
class ComArticlesDatabaseBehaviorAssignable extends KDatabaseBehaviorAbstract
{
    protected function _beforeTableUpdate(KCommandContext $context)
    {
        $data = $context->data;

        if($data->assign)
        {
            $attachments =  $this->getService('com://admin/attachments.model.attachments')
                                ->table('articles')
                                ->row($data->row)
                                ->getRowset();

            $table   = $this->getService('com://admin/articles.database.table.images');
            $image   = $table->select(array('id' => $attachments->get('id')), KDatabase::FETCH_ROW);

            if($image->id != $data->id)
            {
                if($image->id) {
                    $image->delete();
                }

                $table->getRow()->setData(array('id' => $data->id))->save();
            }
            elseif($image->id == $data->id)
            {
                // If it does equal the existing row's id, delete to toggle it off
                $image->delete();
            }
        }

        return true;
    }
}