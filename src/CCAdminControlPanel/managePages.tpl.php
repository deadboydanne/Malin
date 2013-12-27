<h2>Select your page settings</h2>
<?php
if(isset($_POST['DoUpdate'])):?>
<div class='<?=$result[0]?>'><?=$result[1]?></div>
<?php endif; ?>

<form action="<?=create_url('acp', 'managePages')?>" method="post">
	<p><label for="headerName">Type in the name of this webpage:</label><br />
	<input type="text" name="headerName" value="<?php echo $pages[0]['headerTitle']; ?>" /></p>
	<p><label for="headerSlogan">Type in the slogan of this webpage:</label><br />
	<input type="text" name="headerSlogan" value="<?php echo $pages[0]['headerSlogan']; ?>" /></p>
	<p><label for="footerHeadline">Type in the headline of the footer:</label><br />
	<input type="text" name="footerHeadline" value="<?php echo $pages[0]['footerHeadline']; ?>" /></p>
	<h3>Select active pages</h3>
  <p><input name="checkStart" id="checkStart" type="checkbox" <?php if($pages[0]['startActive'] == 'on'){echo 'checked';} ?> /> Startpage<br />
  <input name="checkGuest" id="checkGuest" type="checkbox" <?php if($pages[0]['guestActive'] == 'on'){echo 'checked';} ?> /> Guestbook<br />
  <input name="checkBlog" id="checkBlog" type="checkbox" <?php if($pages[0]['blogActive'] == 'on'){echo 'checked';} ?> /> Blog<br />
  <input name="checkPage1" id="checkPage1" type="checkbox" <?php if($pages[0]['pageOneActive'] == 'on'){echo 'checked';} ?> /> Extra page 1<br />
  <input name="checkPage2" id="checkPage2" type="checkbox" <?php if($pages[0]['pageTwoActive'] == 'on'){echo 'checked';} ?> /> Extra page 2</p>
  <h3>Select page names</h3>
	<p><label for="acronym">Startpage:</label><br />
	<input id="textStart" type="text" name="textStart" value="<?php echo $pages[0]['startName']; ?>" /></p>
    <p><label for="textGuest">Guestbook:</label><br />
    <input id="textGuest" type="text" name="textGuest" value="<?php echo $pages[0]['guestName']; ?>" /></p>
    <p><label for="textBlog">Blog:</label><br />
    <input type="text" name="textBlog" id="textBlog" value="<?php echo $pages[0]['blogName']; ?>" /></p>
    <p><label for="textPage1">Extra page 1:</label><br />
    <input type="text" name="textPage1" id="textPage1" value="<?php echo $pages[0]['pageOneName']; ?>" /></p>
    <p><label for="textPage2">Extra page 2:</label><br />
    <input type="text" name="textPage2" id="textPage2" value="<?php echo $pages[0]['pageTwoName']; ?>" /></p>
    <input type="submit" name="doUpdate" value="Save" />
</form>
