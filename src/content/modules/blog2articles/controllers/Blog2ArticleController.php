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
	public function getCurrentStep() {
		return $_SESSION ["blog2article_step"];
	}
	public function getPercent() {
		$onefile = 100 / floatval ( $this->countTotalArgs () );
		$currentPercent = floatval ( $_SESSION ["blog2article_step"] ) * $onefile;
		return intval ( $currentPercent );
	}
	protected function _importEntry($blogData, $category = 1) {
		try {
			ContentFactory::loadBySystemnameAndLanguage ( $blogData->seo_shortname, $blogData->language );
			return false;
		} catch ( Exception $e ) {
			$article = new Article ();
			$article->systemname = $blogData->seo_shortname;
			$article->language = $blogData->language;
			$article->title = $blogData->title;
			$article->active = $blogData->entry_enabled;
			$article->autor = $blogData->author;
			$article->lastchangeby = $blogData->author;
			$article->content = $blogData->content_full;
			$article->position = $_SESSION ["blog2article_step"] * 10;
			$excerpt = $blogData->content_preview;
			if (strlen ( trim ( strip_tags ( $excerpt ) ) ) <= 0) {
				$excerpt = $article->content;
			}
			$article->excerpt = $excerpt;
			$article->save ();
			$article->created = $blogData->datum;
			$article->lastmodified = $blogData->datum;
			$article->save ();
			return true;
		}
	}
	public function nextStep() {
		$sql = "SELECT * FROM {prefix}blog limit 1 OFFSET ? order by id";
		$args = array (
				$_SESSION ["blog2article_step"] - 1 
		);
		$query = Database::pQuery ( $sql, $args, true );
		if (count ( $query ) > 0) {
			// @TODO Daten importieren
			$blogData = Database::fetchObject ( $query );
			if ($blogData) {
				$this->_importEntry($blogData)
			}
			$_SESSION ["blog2article_step"] += 1;
		}
		$html = Template::executeModuleTemplate ( "blog2article", "progressbar" );
		die ( $html );
	}
}