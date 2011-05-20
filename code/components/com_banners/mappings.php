<?php
/**
 * @version		$Id: mappings.php 1295 2011-05-16 22:58:08Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Factory mappings
 *
 * @author    	Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */

KFactory::map('site::com.banners.model.banners',    'admin::com.banners.model.banners');
KFactory::map('site::com.banners.model.clicks',     'admin::com.banners.model.banners');