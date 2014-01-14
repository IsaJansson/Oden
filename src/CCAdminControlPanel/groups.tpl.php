<h1>Manage groups</h1>
<p>You can view and update all groups.</p>

<?php if($is_authenticated && $user['hasRoleAdmin']): ?>
  <ul>
  <?php foreach($allgroups as $group): ?>
    <li><a href="<?=create_url('acp/editgroups/'.$group['id'])?>"><?=$group['name']?></a> (<?=$group['acronym']?>)
  <?php endforeach; ?>
  </ul>
          <hr>
  <ul>
          <li><a href="<?=create_url('acp/creategroup')?>">Create new group</a></li>
  </ul>
<?php else: ?>
  <p class='denied'>Access denied! Only the Administrator has access to this part.</p>
<?php endif; ?>