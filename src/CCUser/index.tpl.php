<h1>User Controller Index</h1>
<p>One controller to manage the user actions, mainly login, logout, view and edit profile. Use the menu in 
the upper right corner to interact with these controllers.</p>
<?php if($is_authenticated): ?>
	<ul>
	<li><a href='<?=create_url('user/profile')?>'>Your profile</a>
	<li><a href='<?=create_url('user/logout')?>'>Log out</a>
	</ul>
<?php else: ?>
<ul>
  <li><a href='<?=create_url('user/login')?>'>Login</a>
</ul>
<?php endif; ?>