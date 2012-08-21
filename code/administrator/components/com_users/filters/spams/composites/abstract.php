<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Users
 * @copyright      Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Abstract composite spam filter class.
 *
 * @author         Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Users
 */

abstract class ComUsersFilterSpamCompositeAbstract extends ComUsersFilterSpamAbstract
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        foreach ($config->checks as $identifier => $settings)
        {
            if (is_numeric($identifier)) {
                $identifier = $settings;
                $settings   = array();
            }

            // Force a KConfig object.
            $settings = new KConfig($settings);

            // Enqueue the filter.
            $this->addFilter($this->getService($identifier, $settings->toArray()), $settings->priority);
        }
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(array('checks' => array()));
        parent::_initialize($config);
    }

    protected function _validate($data) {
        // Nothing to do.
    }
}