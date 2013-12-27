<h1>Manage group</h1>
<p>You can view and update seleced group.</p>
<?php

if(isset($_POST['doSubmit'])):?>
<div class='<?=$result[0]?>'><?=$result[1]?></div>
<?php endif; ?>

<form action="acp/manageUser" method="post">
	<input type="hidden" value="<?php echo $group[0]['id']; ?>" name="id" />
	<p><label for="acronym">Acronym:</label><br />
    <input type="text" name="acronym" value="<?php echo $group[0]['acronym']; ?>" /></p>
	<p><label for="name">Name:</label><br />
    <input type="text" name="name" value="<?php echo $group[0]['name']; ?>" /></p>
    <p><input type="submit" name="doSubmit" value="Submit" />
    <input type="submit" name="doDelete" value="Delete" /></p>
</form>
  <p>This group was created at <?=$group[0]['created']?> and last updated at <?=$group[0]['updated']?>.</p>
