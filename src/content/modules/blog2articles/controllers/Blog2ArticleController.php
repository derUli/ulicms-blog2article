<?php
use UliCMS\Data\Content\Comment;
use UliCMS\Exceptions\FileNotFoundException;
class Blog2ArticleController extends Controller {
	public function __construct() {
		if (! isset ( $_SESSION ["blog2article_step"] )) {
			$_SESSION ["blog2article_step"] = 1;
		}
	}
	public function countTotalArgs() {
		$sql = "SELECT count(id) as amount FROM {prefix}blog";
		$query = Database::query ( $sql, true );
		$result = Database::fetchObject ( $query );
		return $result->amount;
	}
	public function getCurrentStep() {
		return $_SESSION ["blog2article_step"];
	}
	public function getPercent() {
		if ($this->countTotalArgs () < 1) {
			return 0;
		}
		$onefile = 100 / floatval ( $this->countTotalArgs () );
		$currentPercent = floatval ( $_SESSION ["blog2article_step"] ) * $onefile;
		return intval ( $currentPercent );
	}
	protected function _importEntry($blogData, $category_id = 1) {
		try {
			$article = ContentFactory::getBySystemnameAndLanguage ( $blogData->seo_shortname, $blogData->language );
			return $article->id;
		} catch ( FileNotFoundException $e ) {
			$article = new Article ();
			$article->systemname = $blogData->seo_shortname;
			$article->language = $blogData->language;
			$article->title = $blogData->title;
			$article->active = $blogData->entry_enabled;
			$article->menu = "none";
			$article->author_id = $blogData->author;
			$user = new User ( $blogData->author );
			$article->group_id = $user->getPrimaryGroupId ();
			
			$article->lastchangeby = $blogData->author;
			$article->content = $blogData->content_full;
			$article->position = $_SESSION ["blog2article_step"] * 10;
			$article->meta_description = $blogData->meta_description;
			$article->meta_keywords = $blogData->meta_keywords;
			$article->og_description = $blogData->meta_description;
			$article->og_title = $blogData->title;
			$article->category_id = $category_id;
			
			$excerpt = $blogData->content_preview;
			if (strlen ( trim ( strip_tags ( $excerpt ) ) ) <= 0) {
				$excerpt = $article->content;
			}
			$article->excerpt = $excerpt;
			$article->comments_enabled = $blogData->comments_enabled;
			$article->article_date = $blogData->datum;
			$article->save ();
			return $article->id;
		}
	}
	protected function _importComments($originalId, $contentId) {
		$sql = "SELECT * FROM {prefix}blog_comments where post_id = ? order by id";
		$query = Database::pQuery ( $sql, array (
				$originalId 
		), true );
		while ( $row = Database::fetchObject ( $query ) ) {
			$comment = new Comment ();
			$comment->setContentId ( $contentId );
			$comment->setAuthorName ( $row->name );
			$comment->setAuthorEmail ( $row->email );
			$comment->setAuthorUrl ( $row->url );
			$comment->setText ( $row->comment );
			$comment->setStatus ( CommentStatus::PUBLISHED );
                        $comment->setRead(true);
			$comment->save ();
			
			// Apply date from date comment after save, 
			// since the date would be overwritten on create
			$comment->setDate ( intval($row->date ));
			$comment->save();
		}
	}
	public function nextStep() {
		$acl = new ACL ();
		if (! $acl->hasPermission ( "blog2articles" )) {
			header ( "HTTP/1.0 403 Forbidden" );
			return;
		}
		if (isset ( $_REQUEST ["reset"] )) {
			$_SESSION ["blog2article_step"] = 1;
		}
		$sql = "SELECT * FROM {prefix}blog order by id limit 1 OFFSET ?";
		$args = array (
				$_SESSION ["blog2article_step"] - 1 
		);
		$query = Database::pQuery ( $sql, $args, true );
		if (Database::getNumRows ( $query ) > 0) {
			$blogData = Database::fetchObject ( $query );
			if ($blogData) {
				$insertId = $this->_importEntry ( $blogData, $_REQUEST ["category_id"] );
				$this->_importComments ( $blogData->id, $insertId );
			}
		}
		$html = Template::executeModuleTemplate ( "blog2articles", "progressbar" );
		
		if ($_SESSION ["blog2article_step"] != $this->countTotalArgs ()) {
			$_SESSION ["blog2article_step"] += 1;
		}
		die ( $html );
	}
}
