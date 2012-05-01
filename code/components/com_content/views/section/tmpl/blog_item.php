<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $canEdit   = ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own')); ?>

<?php if ($this->item->params->get('show_title') || $canEdit) : ?>
<h2>
	<?php if ($this->item->params->get('show_title')) : ?>
		<?php if ($this->item->params->get('link_titles') && $this->item->readmore_link != '') : ?>
		<a href="<?php echo $this->item->readmore_link; ?>" class="contentpagetitle<?php echo $this->escape($this->item->params->get( 'pageclass_sfx' )); ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($canEdit) : ?>
	<?php echo JHTML::_('icon.edit', $this->item, $this->item->params, $this->access); ?>
   <?php endif; ?>
</h2>
<?php endif; ?>
<?php  if (!$this->item->params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>
<?php echo $this->item->event->beforeDisplayContent; ?>
<?php if (($this->item->params->get('show_section') && $this->item->sectionid) || ($this->item->params->get('show_category') && $this->item->catid)) : ?>
<p>
	<?php if ($this->item->params->get('show_section') && $this->item->sectionid && isset($this->section->title)) : ?>
	<span>
		<?php if ($this->item->params->get('link_section')) : ?>
			<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->item->sectionid)).'">'; ?>
		<?php endif; ?>
		<?php echo $this->escape($this->section->title); ?>
		<?php if ($this->item->params->get('link_section')) : ?>
			<?php echo '</a>'; ?>
		<?php endif; ?>
			<?php if ($this->item->params->get('show_category')) : ?>
			<?php echo ' - '; ?>
		<?php endif; ?>
	</span>
	<?php endif; ?>
	<?php if ($this->item->params->get('show_category') && $this->item->catid) : ?>
	<span>
		<?php if ($this->item->params->get('link_category')) : ?>
			<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug, $this->item->sectionid)).'">'; ?>
		<?php endif; ?>
		<?php echo $this->escape($this->item->category); ?>
		<?php if ($this->item->params->get('link_category')) : ?>
			<?php echo '</a>'; ?>
		<?php endif; ?>
	</span>
	<?php endif; ?>
</p>
<?php endif; ?>

<?php if ($this->item->params->get('show_create_date') || ($this->item->params->get('show_author') && $this->item->author != "")) : ?>
<p class="timestamp">
<?php if (($this->item->params->get('show_author')) && ($this->item->author != "")) : ?>
	<?php JText::printf( 'Written by', ($this->escape($this->item->created_by_alias) ? $this->escape($this->item->created_by_alias) : $this->escape($this->item->author)) ); ?></span>
<?php endif; ?>

<?php if ($this->item->params->get('show_create_date')) : ?>
	<?php echo KService::get('koowa:template.helper.date')->format(array('date' => $this->item->created, 'format' => JText::_('DATE_FORMAT_LC2'))) ?>
<?php endif; ?>

<?php if ( intval($this->item->modified) != 0 && $this->item->params->get('show_modify_date')) : ?>
	<?php echo JText::sprintf('LAST_UPDATED2', KService::get('koowa:template.helper.date')->format(array('date' => $this->item->modified, 'format' => JText::_('DATE_FORMAT_LC2')))); ?>
<?php endif; ?>
</p>
<?php endif; ?>

<?php echo $this->item->text; ?>

<?php if ($this->item->params->get('show_url') && $this->item->urls) : ?>
	<a href="http://<?php echo $this->escape($this->item->urls) ; ?>" target="_blank"><?php echo $this->escape($this->item->urls); ?></a>
<?php endif; ?>

<?php if ($this->item->params->get('show_readmore') && $this->item->readmore) : ?>
	<a href="<?php echo $this->item->readmore_link; ?>" class="readon">
		<?php if ($this->item->readmore_register) :
			echo JText::_('Register to read more...');
		elseif ($readmore = $this->item->params->get('readmore')) :
			echo $readmore;
		else :
			echo JText::sprintf('Read more...');
		endif; ?></a>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?>
