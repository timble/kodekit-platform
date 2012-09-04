<?php
/**
 * @version     $Id: html.php 5096 2012-08-31 21:00:10Z gergoerdosi $
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

class ComArticlesViewArticlesHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $application = $this->getService('application'); 
        $component   = $this->getService('application.components')->articles;
        
        $this->assign('translatable', $application->getCfg('multilanguage') && $component->isTranslatable());
        
        return parent::display();
    }
}