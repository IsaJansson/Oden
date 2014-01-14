<h1>Content</h1>
<?php if($user['hasRoleUser']): ?>
<p>Here you can Create, edit and delete any of the content.</p>
<?php else:?>
<p>When you are not yet logged in you can only view the content, if you wish to alter it you can login with an existing user or create a new user <a href='<?=create_url('user/login')?>'>here</a>.
<?php endif; ?>
<h2>All content</h2>
<?php if($contents != null):?>
  <ul>
  <?php foreach($contents as $val):?>
    <li><?=$val['id']?>, <?=esc($val['title'])?> by <?=$val['owner']?> <?php if($user['hasRoleUser']): ?><a href='<?=create_url("content/edit/{$val['id']}")?>'>edit</a> <?php endif;?><a href='<?=create_url("page/view/{$val['id']}")?>'>view</a>
  <?php endforeach; ?>
  </ul>
<?php else:?>
  <p>No content exists.</p>
<?php endif;?>

<h2>Actions</h2>
<ul>
  <?php if($user['hasRoleUser']): ?> <li><a href='<?=create_url('content/create')?>'>Create new content</a><?php endif;?>
  <li><a href='<?=create_url('blog')?>'>View as blog</a>
</ul>
