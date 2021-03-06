<script type="text/javascript">

  function enableText(checkBool, textID)
  {
    textFldObj = document.getElementById(textID);
    //Disable the text field
    textFldObj.disabled = !checkBool;
    //Clear value in the text field
    if (!checkBool) { textFldObj.value = ''; }
  }

</script>

<h2>Select your page settings</h2>

<?php if(isset($_SESSION['installPagesErrorMsg'])){
	echo "<h3 class='red'>".$_SESSION['installPagesErrorMsg']."</h3>";
}

if(isset($_POST['DoCreate'])):?>
<div class='<?=$result[0]?>'><?=$result[1]?></div>
<?php endif; ?>

<form action="<?=create_url('module', 'installstyle')?>" method="post">
	<p><label for="headerName">Type in the name of this webpage:</label><br />
	<input type="text" name="headerName" /></p>
	<p><label for="headerSlogan">Type in the slogan of this webpage:</label><br />
	<input type="text" name="headerSlogan" /></p>
	<p><label for="footerHeadline">Type in the headline of the footer:</label><br />
	<input type="text" name="footerHeadline" /></p>
	<h3>Select active pages</h3>
  <p><input name="checkStart" id="checkStart" type="checkbox" onclick="enableText(this.checked, 'textStart');" /> Startpage<br />
  <input name="checkGuest" id="checkGuest" type="checkbox" onclick="enableText(this.checked, 'textGuest');" /> Guestbook<br />
  <input name="checkBlog" id="checkBlog" type="checkbox" onclick="enableText(this.checked, 'textBlog');" /> Blog<br />
  <input name="checkPage1" id="checkPage1" type="checkbox" onclick="enableText(this.checked, 'textPage1');" /> Extra page 1<br />
  <input name="checkPage2" id="checkPage2" type="checkbox" onclick="enableText(this.checked, 'textPage2');" /> Extra page 2</p>
  <h3>Select page names</h3>
	<p><label for="acronym">Startpage:</label><br />
	<input id="textStart" type="text" name="textStart" disabled="disabled" /></p>
    <p><label for="textGuest">Guestbook:</label><br />
    <input id="textGuest" type="text" name="textGuest" disabled="disabled" /></p>
    <p><label for="textBlog">Blog:</label><br />
    <input type="text" name="textBlog" id="textBlog" disabled="disabled" /></p>
    <p><label for="textPage1">Extra page 1:</label><br />
    <input type="text" name="textPage1" id="textPage1" disabled="disabled" /></p>
    <p><label for="textPage2">Extra page 2:</label><br />
    <input type="text" name="textPage2" id="textPage2" disabled="disabled" /></p>
    <input type="submit" name="CreatePages" value="Next" />
</form>
