<table class="table users">
    <tr>
        <th><?= $this->Paginator->sort('User.last_name', 'Name'); ?></th>
        <th><?= $this->Paginator->sort('User.email', 'Email'); ?></th>
        <th><?= $this->Paginator->sort('User.is_active', 'Active'); ?></th>
        <th class="actions"></th>
    </tr>       
    <tbody class="content">
<? 
foreach ($users as $item) : 
    $u = &$item['User'];
    $view_url = $this->App->url(array('action'=>'edit', $u['id']));
?>
    <tr>
        <td><?= h($u['name']) ?></td>
        <td><?= h($u['email']) ?></td>
        <td><?= $u['is_active']; ?></td>
        <td class="actions pull-right">
            <div class="btn-group">
                <?                        
                    echo $this->Html->link('Edit', array('controller' => 'users', 'action'=>'edit', $u['id']), array('class' => 'btn', 'escape' => false)); 								
                    echo $this->Html->link('<i class="fam-status-online"></i> Impersonate', 
                        array('controller' => 'users', 'action' => 'impersonate', $u['id'] ),
                        array('class' => 'btn', 'escape' => false));
                    echo $this->Html->link('Delete', array('controller' => 'users', 'action' => 'delete', $u['id'] ), array('class' => 'btn', 'escape' => false)); 
                ?>
            </div>
        </td>
    </tr>
<? endforeach; ?>
    </tbody>
</table>

    <!--?= $this->element('pagination'); ?-->
    
