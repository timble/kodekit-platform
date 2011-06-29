<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * PHP settings HTML View Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 */
class ComInfoViewPhpsettingsHtml extends ComInfoViewHtml
{
    public function display()
    {
        $this->getToolbar()->setTitle('PHP Settings');

        $this->assign('items', $this->getModel()->getList());

        return parent::display();
    }
}