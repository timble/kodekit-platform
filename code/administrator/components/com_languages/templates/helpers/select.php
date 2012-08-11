<?php

class ComLanguagesTemplateHelperSelect extends KTemplateHelperSelect
{
    /*public static function statuses($selected, $name = 'filter_status', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false)
    {
        // Build list
        $list = array();
        if($allowAny) {
            $list[] = self::option('', JText::_( '- Select Status -' ), 'id', 'title' );
        }

        //$list[] = self::option(Nooku::STATUS_ORIGINAL,   JText::_('Original'),   'id', 'title' );
        $list[] = self::option(Nooku::STATUS_MISSING,    JText::_('Missing'),    'id', 'title' );
        $list[] = self::option(Nooku::STATUS_OUTDATED,   JText::_('Outdated'),   'id', 'title' );
        $list[] = self::option(Nooku::STATUS_COMPLETED,  JText::_('Completed'),  'id', 'title' );
        $list[] = self::option(Nooku::STATUS_PENDING,    JText::_('Pending'),  'id', 'title' );
        //$list[] = self::option(Nooku::STATUS_DELETED,    JText::_('Deleted'),    'id', 'title' );

        // build the HTML list
        return self::genericlist($list, $name, $attribs, 'id', 'title', $selected, $idtag );
    }

    public static function tables($selected, $name = 'filter_table_name', $attribs = array('class' => 'inputbox', 'size' => '1'), $idtag = null, $allowAny = false)
    {
         $tables = KFactory::get('admin::com.nooku.model.nooku')->getTables();
         
         //Don't show the modules
         unset($tables['modules']);
         foreach($tables as & $table) {
         	$table->value = $table->table_name;
         }

        // Build list
        $list = array();
        if($allowAny) {
            $list[] = self::option('', JText::_( '- Select Table -' ) , 'value', 'table_name' );
        }

        $list = array_merge( $list, $tables );

        // build the HTML list
        return self::genericlist($list, $name, $attribs, 'value', 'table_name', $selected, $idtag );
    }*/
}