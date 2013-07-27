<h2>Sign Up Now<br><small>Public quizzes are Free!</small></h2>
<?= $this->Form->create('User', array('class' => 'form-horizontal', 'inputDefaults' => array(
    'label' => array('class' => 'control-label'),
    'div' => array('class' => 'control-group'),
    'between' => '<div class="controls">',
    'after' => '</div>'
))); ?>
<?= $this->Form->input('username'); ?>
<?= $this->Form->input('email'); ?>
<?= $this->Form->input('first_name'); ?>
<?= $this->Form->input('last_name'); ?>
<?= $this->Form->input('password', array('type'=>'password')); ?>
<?= $this->Form->input('confirm_password', array('type'=>'password')); ?>
<div class="control-group">
        <div class="controls">
        <?
                echo $this->Form->button('Sign Up', array('class' => 'btn btn-large btn-success'));         
                echo ' ' . $this->Html->link('Already signed up?', 
                        array('controller' => 'users', 'action' => 'login'), array('class'=>'forgot') );  
                echo $this->Form->end(); 
        ?>
        </div>
</div>

<script type="text/javascript">
        $("#UserUsername").focus();
</script>