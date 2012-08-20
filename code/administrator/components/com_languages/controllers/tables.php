<?php
class ComLanguagesControllerTable extends ComDefaultControllerDefault
{
    protected function _actionEdit(KCommandContext $context)
    {
        // Enabling a component means enabling all tables of that component.
        if($context->data->components_component_id && $context->data->enabled)
        {
            $data = $this->getModel()->component($context->data->components_component_id)->getList();
            if(count($data))
            {
                $data->setData(array('enabled' => (int) $context->data->enabled));
                
                if($data->save() === true) {
    		        $context->status = KHttpResponse::RESET_CONTENT;
    		    } else {
    		        $context->status = KHttpResponse::NO_CONTENT;
    		    }
            }
            else $context->setError(new KControllerException('Resource Not Found', KHttpResponse::NOT_FOUND));
            
            $result = $data;
        }
        else $result = parent::_actionEdit($context);
        
        return $result;
    }
}