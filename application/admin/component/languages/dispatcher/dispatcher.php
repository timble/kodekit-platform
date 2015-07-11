<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Default Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Comments
 */
class LanguagesDispatcher extends Library\Dispatcher
{
    public function canDispatch()
    {
        return true;
    }
}