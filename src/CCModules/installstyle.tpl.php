<h2>Select your own style</h2>
<?php if(isset($_POST['CreatePages'])):?>
<div class='<?=$result[0]?>'><?=$result[1]?></div>
<?php endif; ?>

<form action="<?=create_url('module', 'installfinish')?>" method="post">
	<p><label for="backgroundColor">Background Color:</label><br />
	<input class="color" name="backgroundColor" value="#FFE2B3" /></p>
    <p><label for="foregroundColor">Foreground Color:</label><br />
    <input class="color" name="foregroundColor" value="#D0DCE1" /></p>
    <p><label for="menuSelectedColor">Menu selected color:</label><br />
    <input class="color" name="menuSelectedColor" value="#D0DCE1" /></p>
    <p><label for="headerBottomBorderColor">Header bottom border color:</label><br />
    <input class="color" name="headerBottomBorderColor" value="#FFCE80" /></p>
    <p><label for="menuSelectBorderColor">Menu selected border color:</label><br />
    <input class="color" name="menuSelectBorderColor" value="#999" /></p>
    <p><label for="aColor">Link color:</label><br />
    <input class="color" name="aColor" value="#436370" /></p>
    <p><label for="aHoverColor">Link hover color:</label><br />
    <input class="color" name="aHoverColor" value="#990000" /></p>
    <p><label for="fontColor">Font color:</label><br />
    <input class="color" name="fontColor" value="#000000" /></p>
    <p><label for="font">Select font:</label><br />
    <select name="font">
        <option value="Verdana, Geneva, sans-serif">Verdana, Geneva, sans-serif</option>
        <option value='Georgia, "Times New Roman", Times, serif'>Georgia, Times New Roman, Times, serif</option>
        <option value='"Courier New", Courier, monospace'>Courier New, Courier, monospace</option>
        <option value='"Trebuchet MS", Arial, Helvetica, sans-serif'>Trebuchet MS, Arial, Helvetica, sans-serif</option>
    </select> </p>
    <input type="submit" name="CreateStyle" value="Finish" />
</form>
