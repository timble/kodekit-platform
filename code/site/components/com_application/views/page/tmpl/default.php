<?
/**
 * @version     $Id: default.php 4558 2012-08-11 21:12:47Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!DOCTYPE HTML>
<html lang="<?= $language; ?>" dir="<?= $direction; ?>">

<?= @template('default_head') ?>
<?= @template('com://site/application.view.page.body_'.JRequest::getCmd('tmpl', 'default')) ?>
</html>