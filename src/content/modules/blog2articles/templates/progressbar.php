<?php
$controller = ControllerRegistry::get ( "Blog2ArticleController" );
$percent = $controller->getPercent ();
?><?php

if ($percent >= 100) {
	?>
<!--finish-->
<?php }?><div style="height: 50px; width: 100%; margin-top: 20px;">
	<div style ="background-color: green; height: 50px; width:<?php echo $percent;?>%">
	</div>
	<p><?php
	translate ( "import_dataset_x_from_y", array (
			"%x" => $controller->getCurrentStep (),
			"%y" => $controller->countTotalArgs () 
	) );
	?></p>
</div>