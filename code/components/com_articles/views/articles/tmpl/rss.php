<?php
/**
 * @version        $Id: default.php 3870 2012-06-26 11:41:41Z arunasmazeika $
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
defined('KOOWA' or die('Restricted access'));?>
<? // NO LEADING EMPTY LINES AT THE BE BEGINNING !!! ?>
<? echo @helper('com://site/articles.template.helper.rss.feed', $config); ?>