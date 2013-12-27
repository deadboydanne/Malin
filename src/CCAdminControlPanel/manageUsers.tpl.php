<h1>Select user you want to edit</h1>
<ul>
<?php foreach($userList as $user):?>
  <li><a href='<?=create_url("acp/manageUser/{$user['id']}")?>'><?=$user['name']?></a></li>
<?php endforeach; ?>
</ul>
