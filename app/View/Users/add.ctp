<? $this->assign('css', $this->Html->css('views/users')); ?>

<fieldset>
<?= $this->Form->create('User', array(
    'class' => 'form-horizontal',
    'inputDefaults' => array(
        'label' => array('class' => 'control-label'),
        'div' => array('class' => 'control-group'),
        'between' => '<div class="controls">',
        'after' => '</div>'
    ))); ?>
        <legend><?= __('Add User'); ?></legend>
        <?php
            echo $this->Session->flash();
            echo $this->Form->input('username');
            echo $this->Form->input('first_name');
            echo $this->Form->input('last_name');
            echo $this->Form->input('password');
            echo $this->Form->input('confirm_password', array('type' => 'password'));
            echo $this->Form->input('group_id');
        ?>
        <div class="control-group">
            <div class="controls">
            <?
                echo $this->Form->button('Save', array('class' => 'btn btn-primary'));         
                echo $this->Form->end(); 
            ?>
            </div>
        </div>
</fieldset>
