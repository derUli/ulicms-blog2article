$(function() {
	$("#import-button").click(function() {
		$("#import-button").prop('disabled', true);
		nextStep();
	})

})
function nextStep() {
	$.get("index.php?sClass=Blog2ArticleController&sMethod=nextStep", function(
			result) {
		if (result.indexOf("<!--finish-->") > 0) {
			$("#import-button").prop('disabled', false);
		} else {
			$("#importer_output").html(result);
			nextStep();
		}

	});

}