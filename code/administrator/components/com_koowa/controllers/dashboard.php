<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Koowa
 * @copyright   Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
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
class ComKoowaControllerDashboard extends ComDefaultControllerDefault
{

    /**
     * The tarball package name
     *
     * @TODO Change this to something more dynamic in the future?
     *
     * @var string
     */
    private $__tarball = 'koowa.tar.gz';
    
    /**
     * The current channel
     *
     * @TODO Make this dynamic and/or user configurable
     *
     * @NOTE The assembla SVN trunk url are http://svn2.assembla.com/svn/nooku-framework/trunk/code
     *
     * @var string
     */
    private $__channel = 'http://nooku-framework.svn.sourceforge.net/viewvc/nooku-framework/trunk/code.tar.gz?view=tar';


    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        if(KRequest::get('get.action', 'cmd') == 'update') $this->setAction('update');
    }

    /**
     * Initializer
     *
     * @param   object  KConfig object with configuration options
     */
     protected function _initialize(KConfig $config) {
        $config->append(array(
            'request' => array('layout' => 'default'),
        ));

        parent::_initialize($config);
    }

    /**
     * Display function, setting menu to active even on singular view
     *
     * @param   KCommandContext $context
     */
     public function _actionGet(KCommandContext $context) {

         KRequest::set('get.hidemainmenu', 0);

        return parent::_actionGet($context);
    }


    /**
     * Generic framework update action
     *
     *     Using CURL and SourceForge to get the latest release
     *     and runs a update procedure.
     *
     * @TODO Support the SVN package later,
     *       for increased performance
     *       and to let us update from Assembla.
     */
    protected function _actionUpdate()
    {
        // Import used APIs
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.archive');
        jimport('joomla.installer.installer');


        // Prepare curl
        $curl = $this->getService('com://admin/koowa.helper.curl');
        $curl->addSession($this->__channel, array(CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true));

        // Download tarball package and save it to the /tmp/ folder
        $result = $curl->exec();
        JFile::write(JPATH_ROOT.'/tmp/'.$this->__tarball, $result);
        $curl->clear();

        // Unpack the tarball
        if(JFolder::exists(JPATH_ROOT.'/tmp/code/')) JFolder::delete(JPATH_ROOT.'/tmp/code/');
        JArchive::extract(JPATH_ROOT.'/tmp/'.$this->__tarball, JPATH_ROOT.'/tmp/');
        JFile::delete(JPATH_ROOT.'/tmp/'.$this->__tarball);

        // @TODO This is just a temporary workaround until com_koowa is out of the incubator, and in the trunk
        JFolder::copy(JPATH_COMPONENT_ADMINISTRATOR, JPATH_ROOT.'/tmp/code/administrator/components/com_koowa');
        JFolder::copy(JPATH_ROOT.'/tmp/code/administrator/components/com_koowa/install', JPATH_ROOT.'/tmp/code/install');
        JFile::move(JPATH_ROOT.'/tmp/code/administrator/components/com_koowa/manifest.xml', JPATH_ROOT.'/tmp/code/manifest.xml');
        
        // Install the update
        $installer = JInstaller::getInstance();
        $installer->install(JPATH_ROOT.'/tmp/code/');

        // Cleanup
        JFolder::delete(JPATH_ROOT.'/tmp/code/');
    }
}