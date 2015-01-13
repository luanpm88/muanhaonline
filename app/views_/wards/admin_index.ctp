<div class="wards index">
	<h2><?php __('Wards');?></h2>
	
	<?php echo $this->render('_filter', '');?>	
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('city_id');?></th>
			<th><?php echo $this->Paginator->sort('district_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('order');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($wards as $ward):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $ward['Ward']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($ward['City']['name'], array('controller' => 'cities', 'action' => 'view', $ward['City']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($ward['District']['name'], array('controller' => 'districts', 'action' => 'view', $ward['District']['id'])); ?>
		</td>
		<td><?php echo $ward['Ward']['name']; ?>&nbsp;</td>
		<td><?php echo $ward['Ward']['order']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $ward['Ward']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $ward['Ward']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $ward['Ward']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $ward['Ward']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Ward', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Cities', true), array('controller' => 'cities', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New City', true), array('controller' => 'cities', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Districts', true), array('controller' => 'districts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New District', true), array('controller' => 'districts', 'action' => 'add')); ?> </li>
	</ul>
</div>