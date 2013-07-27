<? $this->assign('css', $this->Html->css('views/users')); ?>
<?= $this->Form->create('User'); ?>

<div class="users form reset">
    <h2><?= __('Reset Password'); ?></h2>
    <div class="controls-row">
        <?= $this->Form->input('password', array('class'=>'input-large'));
              echo $this->Form->input('confirm_password', array('type' => 'password', 'class'=>'input-large')); ?>
    </div>
    <div class="controls-row">
        <div class="control-group">
            <div class="controls">
                <?= $this->Form->button('Reset', array('class' => 'btn btn-success')); ?>
                <?= ' ' . $this->Html->link('Cancel', array('controller' => 'users', 'action' => 'logout'), array('class'=>'forgot') ); ?>
            </div>
        </div>
	</div>
</div>

<?= $this->Form->end(); ?>