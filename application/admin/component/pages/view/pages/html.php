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
 * Pages Html View Class
 *   
 * @author    	Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class PagesViewPagesHtml extends Library\ViewHtml
{
    public function render()
    {
        $this->applications = array_keys(Library\ClassLoader::getInstance()->getApplications());
        $this->menus        = $this->getObject('com:pages.model.menus')->getRowset();
        
        return parent::render();
    }
}