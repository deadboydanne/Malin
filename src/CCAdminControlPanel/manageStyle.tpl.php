<h2>Select your own style</h2>
<?php if(isset($_POST['doSubmit'])):?>
<div class='<?=$result[0]?>'><?=$result[1]?></div>
<?php endif; ?>
<form action="<?=create_url('acp', 'manageStyle')?>" method="post">
	<p><label for="backgroundColor">Background Color:</label><br />
	<input class="color" name="backgroundColor" value="<?php echo $style[0]['backgroundColor']; ?>" /></p>
    <p><label for="foregroundColor">Foreground Color:</label><br />
    <input class="color" name="foregroundColor" value="#<?php echo $style[0]['foregroundColor']; ?>" /></p>
    <p><label for="menuSelectedColor">Menu selected color:</label><br />
    <input class="color" name="menuSelectedColor" value="#<?php echo $style[0]['menuSelectedColor']; ?>" /></p>
    <p><label for="headerBottomBorderColor">Header bottom border color:</label><br />
    <input class="color" name="headerBottomBorderColor" value="#<?php echo $style[0]['headerBottomBorderColor']; ?>" /></p>
    <p><label for="menuSelectBorderColor">Menu selected border color:</label><br />
    <input class="color" name="menuSelectBorderColor" value="#<?php echo $style[0]['menuSelectBorderColor']; ?>" /></p>
    <p><label for="aColor">Link color:</label><br />
    <input class="color" name="aColor" value="#<?php echo $style[0]['aColor']; ?>" /></p>
    <p><label for="aHoverColor">Link hover color:</label><br />
    <input class="color" name="aHoverColor" value="#<?php echo $style[0]['aHoverColor']; ?>" /></p>
    <p><label for="fontColor">Font color:</label><br />
    <input class="color" name="fontColor" value="#<?php echo $style[0]['fontColor']; ?>" /></p>
    <p><label for="font">Select font:</label><br />
    <select name="font">
    	<option value='<?php echo $style[0]['font']; ?>'><?php echo $style[0]['font']; ?></option>
        <option value="Verdana, Geneva, sans-serif">Verdana, Geneva, sans-serif</option>
        <option value='Georgia, "Times New Roman", Times, serif'>Georgia, Times New Roman, Times, serif</option>
        <option value='"Courier New", Courier, monospace'>Courier New, Courier, monospace</option>
        <option value='"Trebuchet MS", Arial, Helvetica, sans-serif'>Trebuchet MS, Arial, Helvetica, sans-serif</option>
    </select> </p>
    <input type="submit" name="doSubmit" value="Finish" />
</form>