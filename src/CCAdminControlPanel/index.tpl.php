<h1>Admin Control Panel</h1>
<?php if($is_authenticated && $user['hasRoleAdmin']): ?>
<p>This is the place to manage the admin related stuff. Here you can see a list of all users, groups and content
and you are able to add, modify and delete users, groups and content.</p>
<p>You can also change which group any specific user should be a  member of and thereby decide how much power that user has on this site.</p>
<p>New users are automaticlly members of The User Group who have the power to change their own profile and add, modify and delete content on the site.</p>
<p>If you do not want them to be able to add, modify and delete content you can change their membership to The Visitor Group who can only view the content.</p>

<?php else: ?>
	<p>Access denied! This part can only be accessed by the Administrator.</p>
<?php endif; ?>
