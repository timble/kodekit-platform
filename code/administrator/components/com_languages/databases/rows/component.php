<?php
class ComLanguagesDatabaseRowComponent extends KDatabaseRowDefault
{
    public function save()
    {
        $result = parent::save();
        
        if($this->getStatus() == KDatabase::STATUS_UPDATED && $this->isModified('enabled') && $this->enabled)
        {
            // If there aren't any tables for the component yet, add them from the manifest file.
            $tables = $this->getService('com://admin/languages.database.table.tables');
            if(!$tables->count(array('components_component_id' => $this->id)))
            {
                $component = $this->getService('com://admin/extensions.database.table.components')
                    ->select($this->id, KDatabase::FETCH_ROW);
                
                $file = $this->getIdentifier()->getApplication('admin').'/components/'.$component->option.'/manifest.xml';
                if(file_exists($file) && $xml = simplexml_load_file($file))
                {
                    if(isset($xml->tables))
                    {
                        $data = array();
                        foreach($xml->tables->children() as $table)
                        {
                            if((string) $table['translatable'] == 'yes')
                            {
                                $data[] = array(
                                    'components_component_id' => $this->id,
                                    'table' => (string) $table
                                );
                            }
                        }
                        
                        if($data) {
                            $tables->getRowset()->addData($data)->save();
                        }
                    }
                }
            }
        }
        
        return $result;
    }
}