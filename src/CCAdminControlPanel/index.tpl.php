<h1>Admin Control Panel</h1>
<?php if($is_authenticated && $user['hasRoleAdmin']): ?>
<p>This is the place to manage the admin related stuff. Here you can see a list of all users, groups and content
and you are able to add, modify and delete users, groups and content.</p>
<?php else: ?>
	<p>Access denied! This part can only be accessed by the Administrator.</p>
<?php endif; ?>
