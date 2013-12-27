<?php
    header("Content-type: text/css; charset: UTF-8");
?>

/* CSS Document */

@import url(../../../themes/grid/style.css);

<?php		
    $db = new PDO('sqlite:../../data/.ht.sqlite', null, null, null);
    $db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	
	$stmt = $db->prepare('SELECT * from adminStyleConfig;');
	$stmt->execute();
	$query = $stmt->fetchAll(PDO::FETCH_ASSOC);	
			
	if($query != null){
	$background = $query[0]['backgroundColor'];
	$foreground = $query[0]['foregroundColor'];
	$menuSelected = $query[0]['menuSelectedColor'];
	$font = $query[0]['font'];
	$headerBottomBorder = $query[0]['headerBottomBorderColor'];
	$aColor = $query[0]['aColor'];
	$menuSelectBorder = $query[0]['menuSelectBorderColor'];
	$aHover = $query[0]['aHoverColor'];
	$fontColor = $query[0]['fontColor'];

	}
	
?>

/** 
 * Description: Sample theme for site which extends the Lydia grid-theme.
 */
 
html{background-color:#<?php echo $background; ?>;}
body{background-color:#<?php echo $foreground; ?>; font-family: <?php echo $font; ?>; color: #<?php echo $fontColor ?>;}
#outer-wrap-header{background-color:#<?php echo $background; ?>;border-bottom:2px solid #<?php echo $headerBottomBorder; ?>;}
#outer-wrap-footer{background-color:#<?php echo $background; ?>;border-top:2px solid #<?php echo $headerBottomBorder; ?>;}
a{color:#<?php echo $aColor; ?>}
#navbar ul.menu li a.selected{background-color:#<?php echo $menuSelected; ?>;border-bottom:none;}
#navbar ul.menu li a:hover {background-color:#<?php echo $menuSelected; ?>;border: 2px solid #<?php echo $menuSelectBorder; ?>;}
a:hover{color:#<?php echo $aHover; ?>;}
h1,h2,h3,h4,h5,h6{font-family: <?php echo $font; ?>; color: #<?php echo $fontColor ?>;}