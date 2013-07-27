<div class="quizzes index">
	<h2><?php echo __('Quizzes'); ?></h2>
	<table class="table">
	<tr>
                <th><?php echo $this->Paginator->sort('user_id'); ?></th>
                <th><?php echo $this->Paginator->sort('name'); ?></th>
                <th><?php echo $this->Paginator->sort('category_id'); ?></th>
                <th><?php echo $this->Paginator->sort('is_private'); ?></th>
                <th><?php echo $this->Paginator->sort('created'); ?></th>
                <th><?php echo $this->Paginator->sort('modified'); ?></th>
                <th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($quizzes as $quiz): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($quiz['User']['id'], array('controller' => 'users', 'action' => 'view', $quiz['User']['id'])); ?>
		</td>
		<td><?php echo h($quiz['Quiz']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($quiz['Category']['name'], array('controller' => 'categories', 'action' => 'view', $quiz['Category']['id'])); ?>
		</td>
		<td><?php echo h($quiz['Quiz']['is_private']); ?>&nbsp;</td>
		<td><?php echo h($quiz['Quiz']['created']); ?>&nbsp;</td>
		<td><?php echo h($quiz['Quiz']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $quiz['Quiz']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $quiz['Quiz']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $quiz['Quiz']['id']), null, 
                                __('Are you sure you want to delete # %s?', $quiz['Quiz']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Quiz'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Questions'), array('controller' => 'questions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Question'), array('controller' => 'questions', 'action' => 'add')); ?> </li>
	</ul>
</div>
