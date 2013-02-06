<?php
/**
 * @version     $Id: menubar.php 4813 2012-08-21 02:25:31Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Template
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationTemplateDefault extends ComDefaultTemplateDefault
{
    public function render($filter = false)
    {
        //Force filter the template data
        return parent::render(true);
    }
}