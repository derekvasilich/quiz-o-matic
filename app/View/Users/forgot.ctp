<h2>Forgot Your Password?</h2>
<?= $this->Form->create('Forgot', array('class'=>'form-horizontal', 'inputDefaults' => array(
    'label' => array('class' => 'control-label'),
    'div' => array('class' => 'control-group'),
    'between' => '<div class="controls">',
    'after' => '</div>'
))); ?>
<?= $this->Form->input('first_name'); ?>
<?= $this->Form->input('last_name'); ?>
<?= $this->Form->input('username'); ?>
<div class="control-group">
        <div class="controls">
        <?
            echo $this->Form->button('Submit', array('class' => 'btn btn-success'));         
            echo ' ' . $this->Html->link('Cancel',
                    array('controller' => 'users', 'action' => 'logout'), array('class'=>'btn btn-link') );  
            echo $this->Form->end(); 
        ?>
        </div>
</div>