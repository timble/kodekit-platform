<?php
class ComArticlesControllerArticle extends ComDefaultControllerDefault
{
    public function setRequest(array $request)
    {
        if(empty($request['layout']))
        {
            if(isset($request['featured']) && (bool) $request['featured'])
            {
                $request['layout'] = 'featured';
                $request['sort']   = 'frontpage.ordering';
            }
            elseif(isset($request['category']) && (int) $request['category']) {
                $request['layout'] = 'category_default';
            } elseif(isset($request['section']) && (int) $request['section']) {
                $request['layout'] = 'section_default';
            }
        }

        if($request['layout'] == 'archived') {
            $request['state'] = -1;
        }

        /*switch($request['layout'])
        {
            case 'category_blog':
            case 'category_default':
            case 'section_blog':
            case 'section_default':
                $request['sort'] = 'created_on';
                $request['direction'] = 'desc';
        }*/

        /*if(KInflector::isPLural($request['view']) && empty($request['limit']))
        {
             $parameters       = $this->getModel()->getParameters();
             $request['limit'] = $parameters->get('num_leading_articles') + $parameters->get('num_intro_articles')
                 + $parameters->get('num_links');
        }*/

        return parent::setRequest($request);
    }
}