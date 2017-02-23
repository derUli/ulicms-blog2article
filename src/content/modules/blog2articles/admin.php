<?php
define ( "MODULE_ADMIN_HEADLINE", "blog2articles" );
define ( "MODULE_ADMIN_REQUIRED_PERMISSION", "blog2articles" );
function blog2articles_admin() {
	echo Template::executeModuleTemplate ( "blog2articles", "import_form" );
	?>
<script type="text/javascript"
	src="<?php echo getModulePath("blog2articles")?>scripts/global.js"></script>
<?php
}
?>
