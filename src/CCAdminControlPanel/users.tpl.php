<h1>Manage users</h1>
<p>You can view and update all users.</p>

<?php if($is_authenticated && $user['hasRoleAdmin']): ?>
  <ul>
  <?php foreach($allusers as $user): ?>
    <li><a href="<?=create_url('acp/edit/'.$user['id'])?>"><?=$user['name']?></a> 
  <?php endforeach; ?>
  </ul>
          <hr>
  <ul>
          <li><a href="<?=create_url('acp/create')?>">Create new user</a></li>
  </ul>
<?php else: ?>
  <p class='denied'>Access denied! Only the Administrator has access to this part.</p>
<?php endif; ?>