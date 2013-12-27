<h2>Create admin account</h2>
<?php if(isset($_SESSION['installErrorMsg'])){
	echo "<h3 class='red'>".$_SESSION['installAdminErrorMsg']."</h3>";
}
?>
<form action="<?=create_url('module', 'installpages')?>" method="post">
	<p><label for="acronym">Acronym:*</label><br />
	<input type="text" name="disabled" value="admin" disabled="disabled" /></p>
    <input type="hidden" name="acronym" value="admin" />
    <p><label for="password">Password:*</label><br />
    <input type="password" name="password" /></p>
    <p><label for="password1">Password again:*</label><br />
    <input type="password" name="password1" /></p>
    <p><label for="name">Name:*</label><br />
    <input type="text" name="name" /></p>
    <p><label for="email">E-mail:*</label><br />
    <input type="text" name="email" /></p>
    <input type="submit" name="DoCreate" value="Next" />
</form>
