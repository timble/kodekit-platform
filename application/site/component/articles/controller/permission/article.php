<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;
use Kodekit\Platform\Application;

/**
 * Article Controller Permission
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Articles
 */
class ControllerPermissionArticle extends Application\ControllerPermissionAbstract
{
    public function canRead()
    {
        $result  = true;
        $article = $this->getModel()->fetch();

        if (!$article->isNew())
        {
            //If user doesn't have access to it, deny access.
            if ($article->access > (int) $this->getUser()->isAuthentic()) {
                $result = false;
            }

            // Users can read their own articles regardless of the state
            if ($article->created_by == $this->getUser()->getId()) {
                $result = true;
            }
        }

        return $result;
    }
}