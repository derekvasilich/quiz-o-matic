<? $this->assign('css', $this->Html->css('views/users')); ?>

<div class="users form">
<?= $this->Form->create('User', array(
    'class' => 'form-horizontal',
    'inputDefaults' => array(
        'label' => array('class' => 'control-label'),
        'div' => array('class' => 'control-group'),
        'between' => '<div class="controls">',
        'after' => '</div>'
    ))); ?>
    <fieldset>
        <legend><?= __('Edit User'); ?></legend>
        <?php 
            echo $this->Form->input('id');
            echo $this->Form->input('username');
            echo $this->Form->input('email');
            echo $this->Form->input('first_name');
            echo $this->Form->input('last_name');
            echo $this->Form->input('password', array('type'=>'password'));
            echo $this->Form->input('confirm_password', array('type'=>'password'));
            echo $this->Form->input('group_id');
        ?>
    </fieldset>
    <div class="control-group">
        <div class="controls">
        <?
            echo $this->Form->button('Save', array('class' => 'btn btn-primary'));         
            echo $this->Form->end(); 
        ?>
        </div>
    </div>
</div>
