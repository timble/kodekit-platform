<?php
/**
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Translation Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */

class ComLanguagesDatabaseRowTranslation extends KDatabaseRowTable
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