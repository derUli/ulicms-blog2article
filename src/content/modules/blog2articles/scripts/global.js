$(function() {
	$("#import-button").click(function() {
		$("#import-button").prop('disabled', true);
		nextStep(true);
	})

})
function nextStep(reset) {
	url = "index.php?sClass=Blog2ArticleController&sMethod=nextStep&";
	url += "&category=" + $("select[name='category']").val();
	if (reset) {
		url += "&reset";
	}
	$.get(url, function(result) {
		if (result.indexOf("<!--finish-->") >= 0) {
			$("#importer_output").html(result);
			$("#import-button").prop('disabled', false);
		} else {
			$("#importer_output").html(result);
			nextStep(false);
		}
		;
	});

}
