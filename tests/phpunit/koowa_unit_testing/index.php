<?php
/**
 * @version     $Id$
 * @package     Koowa_Tests
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */
?>
<h1>Run <strong>testkoowa</strong> from the command line!</h1>
<pre>
<?php
ob_start();
include 'help/help.php';
echo htmlentities(ob_get_clean());
?>
</pre>
