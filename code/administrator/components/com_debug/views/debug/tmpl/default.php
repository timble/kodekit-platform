<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_debug/css/debug-default.css" />

<div id="debug" class="profiler -koowa-box-vertical -koowa-box-flex">
<?=	@helper('tabs.startPane', array('id' => 'debug')); ?>
<?= @helper('tabs.startPanel', array('title' => 'Profiles', 'attribs' => array( 'class' => 'icon icon-32-timeline'))); ?>
	<h4><?= @text('Profile Information' ) ?></h4>
	<? foreach ( KFactory::get('lib.koowa.profiler')->getMarks() as $mark ) : ?>
		<div><?= $mark ?></div>
	<? endforeach; ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Memory', 'attribs' => array( 'class' => 'icon icon-32-profiles'))); ?>
	<h4><?= @text('Memory Usage' ) ?></h4>
	<?=  KFactory::get('lib.koowa.profiler')->getMemory(); ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Queries', 'attribs' => array( 'class' => 'icon icon-32-storage'))); ?>
	<h4><?= JText::sprintf( 'Queries logged',  count(KFactory::get('admin::plg.event.debug')->queries)) ?></h4>
	<ol>
	<? foreach (KFactory::get('admin::plg.event.debug')->queries as $k => $sql) : ?>
	<li>
		<pre><code class="language-sql"><?= preg_replace('/(FROM|LEFT|INNER|OUTER|WHERE|SET|VALUES|ORDER|GROUP|HAVING|LIMIT|ON|AND)/', '<br />\\0', $sql); ?></code></pre>
	</li>
	<? endforeach; ?>
	</ol>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Languages', 'attribs' => array( 'class' => 'icon icon-32-resources'))); ?>
	<h4><?= @text( 'Language Files Loaded' ) ?></h4>
	<ul>
	<? foreach ( KFactory::get('lib.koowa.language')->getPaths() as $extension => $files) : ?>
		<? foreach ( $files as $file => $status ) : ?>
			<li><?= $file ?></li>
		<? endforeach; ?>
	<? endforeach; ?>
	</ul>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Strings', 'attribs' => array( 'class' => 'icon icon-32-audits'))); ?>
	<h4><?= @text( 'Untranslated Strings Diagnostic' ) ?></h4>
	<pre>
	<? foreach (KFactory::get('lib.koowa.language')->getOrphans() as $key => $occurance) : ?>
		<? foreach ( $occurance as $i => $info) : ?>
		<?	
			$class	= @$info['class'];
			$func	= @$info['function'];
			$file	= @$info['file'];
			$line	= @$info['line'];
		?>
		<?= strtoupper( $key )."\t$class::$func()\t[$file:$line]\n"; ?>
		<? endforeach; ?>
	<? endforeach; ?>
	</pre>
	
<?= @helper('tabs.endPanel'); ?>
<?= @helper('tabs.endPane'); ?>
</div>