<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Component Theme Override Locator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\TemplateLoaderComponent
 */
class TemplateLocatorComponent extends Library\TemplateLocatorComponent
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'theme_path' => ''
        ));

        parent::_initialize($config);
    }

    /**
     * Locate the template based on a virtual path
     *
     * @param  string $path  Stream path or resource
     * @param  string $base  The base path or resource (used to resolved partials).
     * @throws \RuntimeException If the no base path was passed while trying to locate a partial.
     * @return string   The physical stream path for the template
     */
    public function locate($path, $base = null)
    {
        $result = parent::locate($path, $base);

        $theme_path = $this->getConfig()->theme_path;
        if(!empty($theme_path))
        {
            $root_path = \Nooku::getInstance()->getRootPath();
            $base_path = \Nooku::getInstance()->getBasePath();

            //Theme override
            $file_path = str_replace(array($base_path.'/component', $root_path.'/component', '/view', '/templates'), '', $result);

            if ($override = $this->realPath($theme_path.'/templates/'.$file_path)) {
                $result = $override;
            }
        }

        return $result;
    }
}