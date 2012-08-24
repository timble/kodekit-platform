<?php
class ComLanguagesControllerComponent extends ComDefaultControllerResource
{
    protected function _actionEdit(KCommandContext $context)
    {
        $request = $this->getRequest();
        if($id = KConfig::unbox($request->id))
        {
            $this->getService('com://admin/languages.model.tables')
                ->component($id)
                ->getList()
                ->setData(array('enabled' => (int) $context->data->enabled))
                ->save();
        }
    }
}