<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?= helper('behavior.validator') ?>

<script inline>
window.addEvent('domready', function(){
	/* Reset the filter values to blank */
	document.id('activities-filter').addEvent('reset', function(e){
		e.target.getElements('input').each(function(el){
			if(['day_range','end_date', 'user'].contains(el.name)){
				el.value = '';
			}
		});
		e.target.submit();
	});
});
</script>


<h3><?=translate( 'Extensions' )?></h3>
<ul class="navigation">
    <li>
    <a class="<?= empty($state->package) ? 'active' : ''; ?>" href="<?= route('package=') ?>">
    <?= translate('All extensions')?>
    </a></li>
    <?php foreach ($packages as $package): ?>
    <li><a <?= $package->id == $state->package ? 'class="active"' : '' ?> href="<?=route('package='.$package->id)?>"><?=ucfirst($package->package)?></a></li>
    <?php endforeach ?>
</ul>

<form action="" method="get" id="activities-filter">
    <fieldset>
        <legend><?=translate( 'Filters' )?></legend>
        <div class="input-prepend">
            <span class="add-on"><?=translate('Show until')?></span>
            <input type="date" name="end_date" value="<?= $state->end_date ?>" />
        </div>
        <div class="input-prepend">
            <span class="add-on"><?=translate('Going back')?></span>
            <input type="text" name="day_range" value="<?=($state->day_range) ? $state->day_range : '' ?>" />
        </div>
        <div class="input-prepend">
            <span class="add-on">User</span>
            <?= helper('com:users.listbox.users',
                array(
                    'autocomplete' => true,
                    'name'		   => 'user',
                    'validate'     => false,
                    'attribs'      => array('size' => null),
                )) ?>
        </div>
        <div class="btn-group">
            <input type="submit" name="submitfilter" class="btn" value="<?=translate('Filter')?>" />
            <input type="reset" name="cancelfilter" class="btn" value="<?=translate('Reset')?>" />
        </div>
    </fieldset>
</form>
