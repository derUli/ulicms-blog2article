<?php
class Blog2ArticleController extends Controller {
	public function __construct() {
		if (! isset ( $_SESSION ["blog2article_step"] )) {
			$_SESSION ["blog2article_step"] = 1;
		}
	}
	public function countTotalArgs() {
		$query = "SELECT count(id) as amount FROM {prefix}blog";
		$result = Database::fetchObject ( $query );
	}
	public function getPercent() {
		$onefile = 100 / floatval ( $this->countTotalArgs () );
		$currentPercent = floatval ( $_SESSION ["blog2article_step"] ) * $onefile;
		return intval ( $currentPercent );
	}
	public function nextStep() {
		$sql = "SELECT * FROM {prefix}blog limit 1 OFFSET ? order by id";
		$args = array (
				$_SESSION ["blog2article_step"] - 1 
		);
		$query = Database::pQuery ( $sql, $args, true );
		if (count ( $query ) > 0) {
			// Todo Daten importieren
			// @FIXME HTML-Code sollte nicht im Controller stehen
			die ( '<div style="background-color:green;height:50px; width:' . $this->getPercent () . '%"></div>' );
			$_SESSION ["blog2article_step"] += 1;
		} else {
			// @FIXME HTML-Code sollte nicht im Controller stehen
			die ( '<!--finish--><div style="background-color:green;height:50px; width:' . intval ( 100 ) . '%"></div>' );
		}
	}
}