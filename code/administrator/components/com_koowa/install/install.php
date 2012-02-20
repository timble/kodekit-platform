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

// no direct access
defined('_JEXEC') or die('Restricted access');

// In order to allow multiple components being installed in go, this check is crucial
if(!function_exists('com_install'))
{
    /**
     * This function is required by JInstallerComponent in order to run this script
     * 
     * @return  void
     */
    function com_install()
    {
        return JFactory::getApplication()->get('com_install') !== false;
    }
}

// Define variables
$database   = JFactory::getDBO();
$manifest   = simplexml_load_file($this->parent->getPath('manifest'));
$package    = (string) $manifest->name;
$source     = $this->parent->getPath('source'); // The install package dir
$version    = (string) $manifest->version;
$logo       = JURI::root(1).'/media/com_koowa/images/logo.png';
$jversion   = JVersion::isCompatible('1.6.0') ? 'joomla' : 'legacy';

//Run checks to see if the server meets system requirements, pause installation if it don't
require JPATH_ADMINISTRATOR.'/components/com_'.$package.'/install/check.php';
//Don't run rest of the script if something failed
if(JFactory::getApplication()->get('com_install') === false) {
    return;
}

//Run platform specific procedures
require JPATH_ADMINISTRATOR.'/components/com_'.$package.'/install/install.'.$jversion.'.php';

// Check if mysqli is active, if not, then enable it
if(JFactory::getApplication()->getCfg('dbtype') != 'mysqli')
{
    $path = JPATH_CONFIGURATION.DS.'configuration.php';
    if(JFile::exists($path))
    {
        JPath::setPermissions($path, '0777');
        $search     = JFile::read($path);
        $replaced   = str_replace('var $dbtype = \'mysql\';', 'var $dbtype = \'mysqli\';', $search);
        JFile::write($path, $replaced);
        JPath::setPermissions($path, '0644');
    }
    JError::raiseNotice(0, JText::_("Database configuration setting changed to 'mysqli'."));
}

// Recording changes that will be reported to the end user
$reports = array();

// Copy over the folders for the fw, com_default, mod_default
foreach ($manifest->framework->folder as $folder)
{
    $from   = isset($folder['src']) ? $folder['src'] : $folder;
    JFolder::copy($source.$from, JPATH_ROOT.$folder, false, true);
    $reports[] = array('href' => $folder, 'type' => 'folder');
}

foreach ($manifest->framework->file as $file)
{
    $folder = JPATH_ROOT.dirname($file);
    if(!JFolder::exists($folder)) JFolder::create($folder);
    
    JFile::copy($source.$file, JPATH_ROOT.$file);
    $reports[] = array('href' => $file, 'type' => 'file');
}

// Preload the logo
$document = JFactory::getDocument();
$document->addScriptDeclaration('(new Image).src = "'.$logo.'";');

// If we have additional packages, move them to a safe place (or JInstaller will delete them)
// and later install them by using KInstaller
$packages = false;
if(JFolder::exists($source.'/packages'))
{
    $packages = JPATH_ADMINISTRATOR.'/components/com_'.$package.'/packages';
    if(JFolder::exists($packages)) JFolder::delete($packages);
    JFolder::copy($source.'/packages', JPATH_ADMINISTRATOR.'/components/com_'.$package.'/packages');
    JFolder::delete($source.'/packages');

}

$config = JFactory::getConfig();
$class = $config->getValue('debug', null) ? 'debug' : null; ?>

<link rel="stylesheet" href="<?php echo JURI::root(1) ?>/media/com_koowa/css/install.css?cache=<?php echo rand() ?>" />

<?php if($packages) : ?>
    <script type="text/javascript">
        (function(version){
            document.write(unescape('%3Cscript type="text/javascript" src="<?php echo JURI::root(1) ?>/media/com_koowa/js/install.'+version+'.js"%3E%3C/script%3E'));
        })(MooTools.version.split('.').length<3 ? 1.5 : 1.6);
    </script>
    
    <div id="install" class="<?php echo $class ?>"><h2 class="working"><?php echo JText::_('Please wait, checking for additional files to install'); ?></h2></div>
<?php endif ?>

<div class="-koowa-install-success">
    <img src="<?php echo $logo ?>" alt="<?php echo JText::_('Nooku Framework Logo') ?>" width="190" height="80" />
    <h1><?php echo JText::_('Installed successfully!') ?></h1>
    <?php if($reports) : ?>
        <h2><?php echo JText::_('The following files and folders were added:') ?></h2>
        <ul>
        <?php foreach ($reports as $report) : ?>
            <li class="<?php echo $report['type'] ?>" title="<?php echo JPATH_ROOT.$report['href'] ?>">
                <?php echo $report['href'] ?>
            </li>
        <?php endforeach ?>
        </ul>
    <?php endif ?>
</div>