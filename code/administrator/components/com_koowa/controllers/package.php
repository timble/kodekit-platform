<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Koowa
 * @copyright   Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Dashboard Controller
 *
 * Have an update action which lets you update the framework remotely.
 *
 * @author      Stian Didriksen <stian@ninjaforge.com>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Koowa
 */
class ComKoowaControllerPackage extends ComDefaultControllerDefault
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        $config->persistent = false;

        parent::__construct($config);

        // Register apis
        Jloader::register('JInstaller', JPATH_LIBRARIES.'/joomla/installer/installer.php');
        Jloader::register('JArchive', JPATH_LIBRARIES.'/joomla/filesystem/archive.php');
        Jloader::register('JFile', JPATH_LIBRARIES.'/joomla/filesystem/file.php');
    }

    /**
     * Display the view
     *
     * @return void
     */
    public function _actionGet(KCommandContext $context)
    {
        if(!JFolder::exists(JPATH_COMPONENT_ADMINISTRATOR.'/packages/')) return;
    
        $model = $this->getModel();
        $data  = array();
        
        if(KRequest::has('get.folder', 'cmd'))
        {
            $package   = JPATH_COMPONENT_ADMINISTRATOR.'/packages/'.KRequest::get('get.folder', 'cmd') . '/';
            $installer = JInstaller::getInstance();
            $installer->install($package);
            $data['html'] = $installer->get('extension.message');
            JFolder::delete($package);
        }
        elseif(KRequest::has('get.file', 'filename'))
        {
            $package         = JPATH_COMPONENT_ADMINISTRATOR.'/packages/'.KRequest::get('get.file', 'filename');
            $data['package'] = JFile::stripExt(basename($package));
            JArchive::extract($package, JPATH_COMPONENT_ADMINISTRATOR.'/packages/'.$data['package']);
            JFile::delete($package);

            $files = JFolder::files(JPATH_COMPONENT_ADMINISTRATOR.'/packages/'.$data['package'], '\.xml$', 1, true);
            $xml = (is_array($files)) ? end($files) : $files;
            $xml = simplexml_load_file($xml);

            $attribs = $xml->attributes();

            $data['package_name'] = (string) $xml->name . ': ' . ucfirst($attribs['type']);
            $data['package_version'] = (string) '<strong>' . JText::_('OK') . '</strong> - ' .$xml->version . @$xml->version['status'];

            ob_start();
            echo $this->getService('com://admin/koowa.view.packages.html')
                ->set('i', KRequest::get('get.i', 'int', 0))
                ->set('package', $xml->name . ' ' . $xml->version . @$xml->version['status'])
                ->setLayout('installing')
                ->display();
            $data['html'] = ob_get_clean();
        }
        else
        {
            ob_start();
            echo $this->getService('com://admin/koowa.view.packages.html')
                ->setLayout('default')
                ->display();
            $data['html'] = ob_get_clean();

            $packages = $model->getList();

            sort($packages);

            foreach ($packages as $i => $package)
            {
                $data['packages'][] = $package;
                ob_start();
                echo $this->getService('com://admin/koowa.view.packages.html')
                    ->set('i', ++$i)
                    ->set('package', $package)
                    ->setLayout('unpacking')
                    ->display();
                $data['layouts'][] = ob_get_clean();
            }
            
            if($model->getTotal() < 1) $data['html'] = null;
        }

        return json_encode($data);
    }
}