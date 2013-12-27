<?php
$phpVersionCheck = "";
$databaseCheck = "";
$databaseIsWriteable = "";

if(version_compare(phpversion(), '5.0.0', '>=')){
	$phpVersionCheck = array('success', 'Your PHP version is '.phpversion().'.');
}else{
	$phpVersionCheck = array('error', 'Your PHP version must be 5.0.0 or above. Your version is '.phpversion().'.');
}

if(is_writeable(MALIN_SITE_PATH.'/data/') && is_writeable(MALIN_SITE_PATH.'/data/.ht.sqlite')){
	$databaseIsWriteable = array('success', 'Your database is writeable');
}else{
	$databaseIsWriteable = array('error', 'Your database is not writeable, make sure the site/data folder and .ht.sqlite rights is set to 777');
}

if(filesize(MALIN_SITE_PATH.'/data/.ht.sqlite') > 10){
	$databaseCheck = array('success', 'Your database is configured');
}else{
	$databaseCheck = array('error', 'Your database has not yet been configured please proceed with setup');
}

?>
<h1>Index Controller</h1>
<p>Welcome to Malin index controller.</p>

<h2>Download</h2>
<p>You can download Malin from github.</p>
<blockquote>
<code>git clone git://github.com/deadboydanne/malin.git</code>
</blockquote>
<p>You can review its source directly on github: <a href='https://github.com/deadboydanne/malin'>https://github.com/deadboydanne/malin</a></p>

<h2>Installation</h2>
<p>First you have to make the data-directory writable. This is the place where Malin needs
to be able to write and create files.</p>
<blockquote>
<code>cd malin; chmod 777 site/data</code>
</blockquote>

<div class="<?php echo $phpVersionCheck[0]; ?>"><?php echo $phpVersionCheck[1]; ?></div>
<div class="<?php echo $databaseIsWriteable[0]; ?>"><?php echo $databaseIsWriteable[1]; ?></div>
<div class="<?php echo $databaseCheck[0]; ?>"><?php echo $databaseCheck[1]; ?></div>

<p>Second, Malin has some modules that need to be initialised. You can do this through a 
controller. Point your browser to the following link.</p>
<blockquote>
<?php if($databaseIsWriteable[0] == 'success' && $phpVersionCheck[0] == 'success'): ?>
<h3><a href='<?=create_url('module/install')?>'>Begin setup</a></h3>
<?php else: ?>
<h3>Make sure your php version is correct and the database is writeable to continue</h3>
<?php endif; ?>
</blockquote>