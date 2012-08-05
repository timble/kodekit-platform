<?php
class ComPagesViewModuleHtml extends ComDefaultViewHtml
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'auto_assign' => false
        ));

        parent::_initialize($config);
    }

    public function display()
    {
        $pages = $this->getService('com://admin/pages.model.pages')->getList();
        $this->assign('pages', $pages);

        $modules = $this->getService('com://admin/extensions.model.modules')
            ->application('site')
            ->getList();

        $this->assign('modules', $modules);

        $relations = $this->getService($this->getModel()->getIdentifier())
            ->module($this->getModel()->module)
            ->getList();

        $this->assign('relations', $relations);

        return parent::display();
    }
}
