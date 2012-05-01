<?
/**
 * @version		$Id: default_items.php 3537 2012-04-02 17:56:59Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<form action="" method="get" name="adminForm">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<? if ($params->get( 'show_headings' )) : ?>
<tr>
	<td width="5" align="right" class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>">
        <?= @text('Num'); ?>
    </td>
    <td height="20" class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>">
        <?= @helper('grid.sort', array('column' => 'Name')); ?>
	</td>
    <? if ( $params->get( 'show_position' ) ) : ?>
    <td height="20" class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>">
        <?= @helper('grid.sort', array('column' => 'con_position', 'title' => 'Position')); ?>
    </td>
    <? endif; ?>
    <? if ( $params->get( 'show_email' ) ) : ?>
    <td height="20" width="20%" class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>">
        <?= @text( 'Email' ); ?>
	</td>
    <? endif; ?>
    <? if ( $params->get( 'show_telephone' ) ) : ?>
	<td height="20" width="15%" class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>">
        <?= @text( 'Phone' ); ?>
	</td>
	<? endif; ?>
    <? if ( $params->get( 'show_mobile' ) ) : ?>
    <td height="20" width="15%" class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>">
        <?= @text( 'Mobile' ); ?>
	</td>
    <? endif; ?>
    <? if ( $params->get( 'show_fax' ) ) : ?>
    <td height="20" width="15%" class="sectiontableheader<?= @escape($params->get('pageclass_sfx')); ?>">
        <?= @text( 'Fax' ); ?>
	</td>
    <? endif; ?>
</tr>
<? endif; ?>
<? $i = 1; ?>
<? foreach( $contacts as $contact ) : ?>
<tr class="sectiontableentry<?= ($i&1) ? '1' : '2'; ?>">
	<td align="center" width="5">
        <?= $i; ?>	
    </td>
    <td height="20">
		<a href="<?= @route('view=contact&category='.$category->id.':'.$category->slug.'&id='. $contact->id.':'.$contact->slug);?>" class="category<?= @escape($params->get('pageclass_sfx'));?> ">
		    <?= $contact->name; ?>
		</a>
	</td>    
	<? if ( $params->get( 'show_position' ) ) : ?>
	<td>
        <?= @escape($contact->con_position);?>
	</td>
    <? endif; ?>
    <? if ( $params->get( 'show_email' ) ) : ?>
	<td width="20%">
	    <?= $contact->email_to; ?>
	</td>
    <? endif; ?>
    <? if ( $params->get( 'show_telephone' ) ) : ?>
    <td width="15%">
        <?= @escape($contact->telephone); ?>
    </td>
    <? endif; ?>
    <? if ( $params->get( 'show_mobile' ) ) : ?>
	<td width="15%">
        <?= @escape($contact->mobile); ?>
	</td>
    <? endif; ?>
    <? if ( $params->get( 'show_fax' ) ) : ?>
	<td width="15%">
        <?= @escape($contact->fax); ?>
	</td>
    <? endif; ?>
</tr>
<? endforeach; ?>
</table>
</form>