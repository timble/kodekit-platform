<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-languages for the canonical source repository
 */

namespace Kodekit\Component\Languages;

use Kodekit\Library;

/**
 * Translation Model Entity
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Languages
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