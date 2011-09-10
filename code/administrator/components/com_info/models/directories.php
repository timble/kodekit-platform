<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Directories Model Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 */

class ComInfoModelDirectories extends KModelAbstract
{
    public function getList()
    {
        if(!$this->_list)
        {
            $directories = array(
                'administrator/components/' => JPATH_ADMINISTRATOR.'/components',
                'administrator/language/'   => JPATH_ADMINISTRATOR.'/language',
                'administrator/modules/'    => JPATH_ADMINISTRATOR.'/modules',
                'administrator/templates/'  => JPATH_ADMINISTRATOR.'/templates',
                'cache'                     => JPATH_CACHE,
                'components/'               => JPATH_SITE.'/components',
                'images/'                   => JPATH_IMAGES,
                'images/banners'            => JPATH_IMAGES.'/banners',
                'images/stories/'           => JPATH_IMAGES.'/stories',
                'language/'                 => JPATH_SITE.'/language',
                'media/'                    => JPATH_SITE.'/media',
                'modules/'                  => JPATH_SITE.'/modules',
                'plugins/'                  => JPATH_PLUGINS,
                'plugins/content/'          => JPATH_PLUGINS.'/content',
                'plugins/editors/'          => JPATH_PLUGINS.'/editors',
                'plugins/editors-xtd/'      => JPATH_PLUGINS.'/editors-xtd',
                'plugins/search/'           => JPATH_PLUGINS.'/search',
                'plugins/system/'           => JPATH_PLUGINS.'/system',
                'plugins/user'              => JPATH_PLUGINS.'/user',
                'templates/'                => JPATH_THEMES,
                'tmp/'                      => JFactory::getConfig()->getValue('config.tmp_path', JPATH_ROOT.'/tmp')
            );

            foreach(new DirectoryIterator(JPATH_ADMINISTRATOR.'/language') as $language)
            {
                if(!$language->isDir() || substr($language, 0, 1) == '.') continue;

                $directories['administrator/language/'.$language.'/'] = JPATH_ADMINISTRATOR.'/language/'.$language;
            }

            foreach(new DirectoryIterator(JPATH_SITE.'/language') as $language)
            {
                if(!$language->isDir() || substr($language, 0, 1) == '.') continue;

                $directories['language/'.$language.'/'] = JPATH_SITE.'/language/'.$language;
            }

            ksort($directories);

            foreach($directories as $name => $path)
            {
                $rows[] = array(
                    'name'      => $name,
                    'writable'  => is_writable($path)
                );
            }

            $this->_list = KFactory::get('com://admin/info.database.rowset.configuration')
                ->addData($rows, false);
        }

        return $this->_list;
    }
}