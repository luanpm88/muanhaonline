<div class="currencies form">
<?php echo $this->Form->create('Currency');?>
	<fieldset>
		<legend><?php __('Admin Add Currency'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('code');
		echo $this->Form->input('rate');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Currencies', true), array('action' => 'index'));?></li>
	</ul>
</div>