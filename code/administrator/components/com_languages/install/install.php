<?php
/**
 * @category   	Nooku
 * @package     Nooku_Administrator
 * @subpackage  Install
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.eu
 */

function com_install()
{
    KLoader::loadIdentifier('com://admin/languages.defines');
    
    // Add primary language.
    $iso_code   = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
    $site       = JApplicationHelper::getClientInfo(0);
    $path       = JLanguage::getLanguagePath($site->path);
    $xml        = JApplicationHelper::parseXMLLangMetaFile($path.DS.$iso_code.DS.$iso_code.'.xml');
    $lang_name  = preg_replace('/\(.*\)/', '', $xml['name']);
    
    KService::get('com://admin/languages.database.row.language')
        ->setData(array(
            'name'        => $lang_name,
            'native_name' => $lang_name,
            'iso_code'    => $iso_code,
            'image'       => strtolower(substr($iso_code, 3, 2)).'.png'
        ))->save();
    
    // Add default tables.
    $model  = KService::get('com://admin/languages.model.tables');
    $tables = $model->getTranslated();
    
    if(!count($tables))
    {
        $default_tables = array('categories', 'content', 'menu', 'modules', 'sections');
        foreach($default_tables as $table)
        {
            $model->getTable()->getRow()->setData(array(
                            'table_name' => $table
            ))->save();
        }
    }
}