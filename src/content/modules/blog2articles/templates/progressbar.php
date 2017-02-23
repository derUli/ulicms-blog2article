<?php
$controller = ControllerRegistry::get ( "Blog2ArticleController" );
$percent = $controller->getPercent ();
?><?php

if ($percent >= 100) {
	?>
<!--finish-->
<?php }?><div style="height: 50px; width: 100%;"></div>
<div style ="background-color: green; height: 50px; width:<?php echo $percent;?>%">
</div>