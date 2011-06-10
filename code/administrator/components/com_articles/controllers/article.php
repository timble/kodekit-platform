<?php
class ComArticlesControllerArticle extends ComDefaultControllerDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->registerActionAlias('trash'  , 'delete');
        $this->registerActionAlias('restore', 'edit');
    }
}