<h2>Installation finished!</h2>
<?php if(isset($_POST['CreateStyle'])):?>
<div class='<?=$result[0]?>'><?=$result[1]?></div>
<?php endif; ?>

<p>The installation of your own webpage has now finished! Feel free to login to your admin account.</p>

<p><a href='<?=create_url('my')?>'>Click here</a> to go to your startpage.</p>