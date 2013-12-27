<h1>Manage user</h1>
<p>You can view and update a users profile information.</p>
<?php
$groupnames = array();
foreach($user2groups as $ug){
	array_push($groupnames, $ug['name']);
}

if(isset($_POST['changePassword']) || isset($_POST['doSubmit'])):?>
<div class='<?=$result[0]?>'><?=$result[1]?></div>
<?php endif; ?>

<form action="acp/manageUser" method="post">
	<input type="hidden" value="<?php echo $user['id']; ?>" name="id" />
	<p><label for="acronym">Acronym:</label><br />
    <input type="text" name="acronym" value="<?php echo $user['acronym']; ?>" disabled="disabled" /></p>
	<p><label for="password">Password:</label><br />
    <input type="password" name="password" /></p>
	<p><label for="password1">Password again:</label><br />
    <input type="password" name="password1" /></p>
    <p><input type="submit" name="changePassword" value="Change Password" />
	<p><label for="name">Name:</label><br />
    <input type="text" name="name" value="<?php echo $user['name']; ?>" /></p>
	<p><label for="email">E-mail:</label><br />
    <input type="text" name="email" value="<?php echo $user['email']; ?>" /></p>
    <p>Select user membersips:</p>
    <?php
	foreach($groups as $group):?>
    <p><input type="checkbox" name="groups[]" value="<?php echo $group['id']; ?>" <?php if(in_array($group['name'], $groupnames)){echo "checked";} ?> />
    <label for="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></label></p>
    <?php endforeach; ?>
    <p><input type="submit" name="doSubmit" value="Submit" />
    <input type="submit" name="doDelete" value="Delete" /></p>
</form>
  <p>This user was created at <?=$user['created']?> and last updated at <?=$user['updated']?>.</p>
