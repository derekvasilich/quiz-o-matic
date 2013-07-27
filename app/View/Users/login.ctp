<? $this->assign('css', $this->Html->css('views/users')); ?>
<?= $this->Form->create('User', array('class' => 'form-horizontal')); ?>

<?= $this->Form->input('username', array('label' => array('class' => 'control-label'),
		'div' => array('class' => 'control-group'),
		'between' => '<div class="controls"><div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>',
		'after' => '</div></div>')); ?>
<?= $this->Form->input('password', array('label' => array('class' => 'control-label'),
		'div' => array('class' => 'control-group'),
		'between' => '<div class="controls"><div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span>',
		'after' => '</div></div>')); ?>

<div class="control-group">
	<div class="controls">
	<?
		echo $this->Form->button('Login', array('class' => 'btn btn-success'));         
		echo ' ' . $this->Html->link('Forgot your password?', 
			array('controller' => 'users', 'action' => 'forgot'), array('class'=>'forgot') );  
		echo $this->Form->end(); 
	?>
	</div>
</div>

<script type="text/javascript">
	$("#UserUsername").focus();
</script>
