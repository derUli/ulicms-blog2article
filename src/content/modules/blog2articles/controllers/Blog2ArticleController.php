<?php
class Blog2ArticleController extends Controller {
	public function countTotalArgs() {
		$query = "SELECT count(id) as amount FROM {prefix}blog";
		$result = Database::fetchObject ( $query );
	}
	public function nextStep($step = 0) {
		$sql = "SELECT * FROM {prefix}blog where id > ?";
		$args = array (
				intval ( $step ) 
		);
		$query = Database::pQuery ( $sql, $args, true );
		// @FIXME HTML-Code sollte nicht im Controller stehen
		if (count ( $query ) > 0) {
		} else {
			die ( '<!--finish--><div style="background-color:green;height:50px; width:' . intval ( 100 ) . '%"></div>' );
		}
	}
}