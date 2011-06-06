<?php
class ComArticlesTemplateHelperListbox extends KTemplateHelperListbox
{
    public function years($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name' => 'year'
        ))->append(array(
            'selected' => $config->{$config->name}
        ));

        $table = KFactory::get('site::com.articles.database.table.articles');

        $query = $table->getDatabase()->getQuery()
            ->select(array('id', 'YEAR(created) AS year'))
            ->group('year');

        $years = $table->select($query, KDatabase::FETCH_ROWSET);

        $options[] = $this->option(array('text' => JText::_('Year')));

        foreach($years as $year) {
            $options[] = $this->option(array('text' => $year->year, 'value' => $year->year));
        }

        $config->options = $options;

        return $this->optionlist($config);
    }

    public function months($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name' => 'month'
        ))->append(array(
            'selected' => $config->{$config->name}
        ));

        $table = KFactory::get('site::com.articles.database.table.articles');

        $query = $table->getDatabase()->getQuery()
            ->select(array('id', 'MONTH(created) AS month'))
            ->group('month');

        $months = $table->select($query, KDatabase::FETCH_ROWSET);

        $options[] = $this->option(array('text' => JText::_('Month')));

        foreach($months as $month) {
            $options[] = $this->option(array('text' => JText::_(strtoupper(date('F', mktime(0, 0, 0, $month->month, 1)))), 'value' => $month->month));
        }

        $config->options = $options;

        return $this->optionlist($config);
    }
}