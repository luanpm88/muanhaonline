<div class="utilities view">
<h2><?php  __('Utility');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $utility['Utility']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $utility['Utility']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $utility['Utility']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Order'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $utility['Utility']['order']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Utility', true), array('action' => 'edit', $utility['Utility']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Utility', true), array('action' => 'delete', $utility['Utility']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $utility['Utility']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Utilities', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Utility', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
