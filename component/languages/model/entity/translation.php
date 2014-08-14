<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Languages;

use Nooku\Library;

/**
 * Translation Model Entity
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Languages
 */
class ModelEntityTranslation extends Library\ModelEntityRow
{
    /**
     * Status = completed
     */
    const STATUS_COMPLETED = 1;

    /**
     * Status = missing
     */
    const STATUS_MISSING = 2;

    /**
     * Status = outdated
     */
    const STATUS_OUTDATED = 3;
}