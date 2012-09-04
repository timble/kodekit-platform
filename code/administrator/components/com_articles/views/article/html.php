<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Article Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesViewArticleHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $application = $this->getService('application');
        $component   = $this->getService('application.components')->articles;
        
        $this->assign('translatable', $application->getCfg('multilanguage') && $component->isTranslatable());
        
        if($this->translatable)
        {
            $translations = $this->getService('com://admin/languages.model.translations')
                ->table('articles')
                ->row($this->getModel()->getItem()->id)
                ->getList();
            
            $this->assign('translations', $translations);
            $this->assign('languages', $this->getService('application.languages'));
        }
        
        $this->assign('user', JFactory::getUser());
        
        return parent::display();
    }
}