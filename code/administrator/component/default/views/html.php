<?php
/**
 * @version     $Id: default.php 2721 2010-10-27 00:58:51Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Html View
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultViewHtml extends KViewHtml
{
    /**
     * Get the layout
     *
     * @return string The layout name
     */
    public function getLayout()
    {
        return empty($this->_layout) ? KInflector::isSingular($this->getName()) ? 'form' : 'default' :  $this->_layout;
    }
}