<?php if($content['id']):?>
  <h1><?=esc($content['title'])?></h1>
  <p><?=$content->GetFilteredData()?></p>
  <?php if($is_authenticated && $user['hasRoleUser']): ?><p class='smaller-text silent'><a href='<?=create_url("content/edit/{$content['id']}")?>'>edit</a><?php endif;?> </p>
<?php else:?>
  <p>404: No such page exists.</p>
<?php endif;?>