<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Contacts Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Contacts
 */
class ContactsViewContactsHtml extends Library\ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        //Set the pathway
        $page = $this->getObject('application.pages')->getActive();
        if($page->getLink()->query['view'] == 'categories' )
        {
            $category = $this->getCategory();
            $this->getObject('application')->getPathway()->addItem($category->title, '');
        }

        return parent::_actionRender($context);
    }

    public function fetchData(Library\ViewContext $context)
    {
        $context->data->params   = $this->getObject('application')->getParams();
        $context->data->category = $this->getCategory();

        return parent::fetchData($context);
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getObject('com:contacts.model.categories')
                         ->table('contacts')
                         ->id($this->getModel()->getState()->category)
                         ->getRow();

        return $category;
    }
}