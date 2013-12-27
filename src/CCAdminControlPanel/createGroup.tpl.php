<h1>Create group</h1>
<p>You can create a new group here.</p>
<?php

if(isset($_POST['doSubmit'])):?>
<div class='<?=$result[0]?>'><?=$result[1]?></div>
<?php endif; ?>

<form action="createGroup" method="post">
	<p><label for="acronym">Acronym:</label><br />
    <input type="text" name="acronym" /></p>
	<p><label for="name">Name:</label><br />
    <input type="text" name="name" /></p>
    <p><input type="submit" name="doSubmit" value="Create" />
</form>
