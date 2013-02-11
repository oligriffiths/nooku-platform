<?
/**
 * @package     Nooku_Server
 * @subpackage  Menus
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h3><?= @text('Applications') ?></h3>
<nav class="scrollable">
	<? foreach($applications as $application) : ?>
	<a <?= $state->application == $application ? 'class="active"' : '' ?> href="<?= @route('application='.$application) ?>">
	    <?= $application ?>
	</a>
	<? endforeach ?>
</nav>