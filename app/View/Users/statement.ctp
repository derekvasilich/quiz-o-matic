<? $this->assign('css', $this->Html->css('views/users')); ?>

<h2><?= h($title) ?></h2>

<p><?= h($statement) ?></p>

<div class="control-group">
    <div class="controls">
    <?
        echo $this->Form->create('Statement', array('class' => 'form'));
        echo $this->Form->hidden('agree', array('value' => '1'));
        echo $this->Form->button('Accept', array('class' => 'btn btn-success')); 
        echo ' ' . $this->Html->link('Cancel', 
                array('controller' => 'users', 'action' => 'logout'), array('class'=>'btn btn-link') );  
        echo $this->Form->end(); 
    ?>
    </div>
</div>
