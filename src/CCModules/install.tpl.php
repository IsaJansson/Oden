<h1>Install Modules</h1>
<p>The following modules was affected by this action:</p>
<table>
<caption>Results from installing modules.</caption>
<thead>
  <tr><th>Module</th><th>Result</th></tr>
</thead>
<tbody>
<?php foreach($modules as $module): ?>
  <tr><td><?=$module['name']?></td><td><div class='<?=$module['result'][0]?>'><?=$module['result'][1]?></div></td></tr>
<?php endforeach; ?>
</tbody>
</table>
