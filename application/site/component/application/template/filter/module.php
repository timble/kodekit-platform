<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Module Template Filter Class
 *
 * @author    	Johan Janssens <http://github.com/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationTemplateFilterModule extends Pages\TemplateFilterModule
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'modules' => 'application.modules',
        ));

        parent::_initialize($config);
    }
}