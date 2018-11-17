<form method="get" class="#">
	<div class="checkbox">
		<label><input type="checkbox" name="import_blog_entries"
			id="import_blog_entries" value="1" onclick="return false;" checked><?php translate("import_blog_entries");?></label>
	</div>
	<div class="checkbox">
		<label><input type="checkbox" name="import_comments"
			id="import_comments" value="1"><?php translate("import_comments");?></label>
	</div>
	<h2><?php translate("target");?></h2>
	<strong><?php translate("category")?></strong>
	<div style="margin-bottom: 20px">
	<?php echo categories :: getHTMLSelect();?>
	</div>
	<input type="button" value="<?php translate("do_import");?>"
		id="import-button">
</form>
<div id="importer_output"></div>
