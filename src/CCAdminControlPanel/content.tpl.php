<h1>Manage content</h1>
<p>You can view and update all content on this database</p>

<?php if($is_authenticated && $user['hasRoleAdmin']): ?>
  <ul>
  <?php foreach($allcontent as $content): ?>
    <li><a href="<?=create_url('content/edit/'.$user['id'])?>"><?=$content['title']?></a> (<?=$content['type']?>) (<?=$content['created']?>)
  <?php endforeach; ?>
  </ul>
          <hr>
  <ul>
          <li><a href="<?=create_url('content/create')?>">Create new content</a></li>
  </ul>
<?php else: ?>
  <p class='denied'>Access denied! Only the Administrator has access to this part.</p>
<?php endif; ?>