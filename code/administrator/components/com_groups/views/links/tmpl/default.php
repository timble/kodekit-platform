<? /** $Id$ */ ?>
<? defined( 'KOOWA' ) or die( 'Restricted access' ) ?>

<style src="media://lib_koowa/css/koowa.css" />
<style src="media://com_groups/css/groups.links.css" />

<script src="media://lib_koowa/js/koowa.js" />
<script src="media://com_groups/js/raphael.js" />
<script src="media://com_groups/js/mapper.js" />

<script>
window.addEvent("domready", function(){
    new Mapper(<?= json_encode(array(
        'holder' => 'links',
        'data'   => array(
            'from'  => $from,
            'to'    => $to,
            'links' => array()
        )
    )) ?>);
});
</script>

<div id="links"></div>