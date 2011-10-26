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

$errors = array();
if(extension_loaded('suhosin'))
{
    //Attempt setting the whitelist value
    @ini_set('suhosin.executor.include.whitelist', 'tmpl://, file://');

    //Checking if the whitelist is ok
    if(!@ini_get('suhosin.executor.include.whitelist') || strpos(@ini_get('suhosin.executor.include.whitelist'), 'tmpl://') === false)
    {
        $this->parent->abort(sprintf(JText::_('The install failed because your server has Suhosin loaded, but it\'s not configured correctly. Please follow <a href="%s" target="_blank">this</a> tutorial before you reinstall.'), 'https://nooku.assembla.com/wiki/show/nooku-framework/Known_Issues'));
        return false;
    }
}

if (!class_exists('mysqli'))
{
    $this->parent->abort(JText::_("We're sorry but your server isn't configured with the MySQLi database driver. Please contact your host and ask them to enable MySQLi for your server."));
    JFactory::getApplication()->set('com_install', false);
    return;
}

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

// Define variables
$database   = JFactory::getDBO();
$type       = 'com';
$manifest   = simplexml_load_file($this->parent->getPath('manifest'));
$package    = (string) $manifest->name;
$source     = $this->parent->getPath('source'); // The install package dir
$version    = (string) $manifest->version;
$logo       = JURI::root(1).'/media/com_koowa/images/logo.png';
$jversion   = JVersion::isCompatible('1.6.0') ? '1.6' : '1.5';

//Run platform specific procedures
require JPATH_ROOT.'/administrator/components/com_'.$package.'/install/install.'.$jversion.'.php';

//Fail the app if errors happened
if($errors) {
    //require '';
}

// Copy over the folders for the fw, com_default, mod_default
foreach ($manifest->framework->folder as $folder)
{
    $from   = isset($folder['src']) ? $folder['src'] : $folder;
    JFolder::copy($source.$from, JPATH_ROOT.$folder, false, true);
}

foreach ($manifest->framework->file as $file)
{
    $folder = JPATH_ROOT.dirname($file);
    if(!JFolder::exists($folder)) JFolder::create($folder);
    
    JFile::copy($source.$file, JPATH_ROOT.$file);
}

$versiontext = '<em>'.JText::_('You need at least %s to run Nooku Framework. You have: %s').'</em>';

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

<link rel="stylesheet" href="<?php echo JURI::root(1) ?>/media/com_koowa/css/install.css" />

<?php if($packages) : ?>
    <script type="text/javascript">
        (function(version){
            document.write(unescape('%3Cscript type="text/javascript" src="<?php echo JURI::root(1) ?>/media/com_koowa/js/install.'+version+'.js"%3E%3C/script%3E'));
        })(MooTools.version.split('.').length<3 ? 1.5 : 1.6);
    </script>
    
    <div id="install" class="<?php echo $class ?>"><h2 class="working"><?php echo JText::_('Please wait, checking for additional files to install'); ?></h2></div>
<?php endif ?>

<table>
    <tbody valign="top">
        <tr>
            <td>
                
                <img src="<?php echo $logo ?>" alt="<?php echo JText::_('Nooku Framework Logo') ?>" width="190" height="80" />
            </td>
            <td width="100%">
                <table class="adminlist">
                    <thead>
                        <tr>
                            <th><?php echo JText::_('Task') ?></th>
                            <th width="30%"><?php echo JText::_('Status') ?></th>
                        </tr>
                    </thead>
                    <tbody id="tasks">
                        <tr class="row0">
                            <td class="key hasTip" title="<?php echo JText::_('PHP Version') ?>::<?php echo sprintf($versiontext, 'PHP v5.2', phpversion()) ?>"><?php echo JText::_('PHP Version') ?></td>
                            <td>
                                <?php echo version_compare(phpversion(), '5.2', '>=')
                                    ? '<strong>'.JText::_('OK').'</strong> - '.phpversion()
                                    : sprintf($versiontext, 'PHP v5.2', phpversion()) ?>
                            </td>
                        </tr>
                        <tr class="row1">
                            <td class="key hasTip" title="<?php echo JText::_('MySQL server Version') ?>::<?php echo sprintf($versiontext, 'MySQL v5.0.41', $database->getVersion()) ?>"><?php echo JText::_('MySQL server Version') ?></td>
                            <td>
                                <?php echo version_compare($database->getVersion(), '5.0.41', '>=')
                                ? '<strong>'.JText::_('OK').'</strong> - '.$database->getVersion()
                                : sprintf($versiontext, 'MySQL server v5.0.41', $database->getVersion()) ?>
                            </td>
                        </tr>
                        <tr class="row0">
                            <td class="key"><?php echo JText::_('Nooku Framework') ?></td>
                            <td><strong><?php echo JText::_('Installed') ?></strong> - <?php echo $version ?></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>