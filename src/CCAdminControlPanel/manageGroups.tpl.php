<h1>Select group you want to edit</h1>
<ul>
<?php foreach($groupList as $group):?>
  <li><a href='<?=create_url("acp/manageGroup/{$group['id']}")?>'><?=$group['name']?></a></li>
<?php endforeach; ?>
</ul>
