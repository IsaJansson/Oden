<div class='box'>
<?php if($is_authenticated && $user['hasRoleAdmin']): ?>
	<h4>Manage users, groups and content</h4>

  <ul>
  <li><a href="<?=create_url('acp/users')?>">Manage users</a> --></li>
  <li><a href="<?=create_url('acp/groups')?>">Manage groups</a> --></li>
  <li><a href="<?=create_url('acp/content')?>">Manage content</a> --></li>
  </ul>
<?php endif; ?>
</div>