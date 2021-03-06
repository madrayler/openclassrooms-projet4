<?php

//require_once "model/Comment.php";

class commentDAO extends BaseDAO
{

    public function getById($commentId)
    {
        $commentDB = $this->db
            ->query('SELECT * FROM comments WHERE id = ' . $commentId)
            ->fetch();
        return new Comment($commentDB['id'], $commentDB['article_id'], $commentDB['pseudonyme'], $commentDB['comments'], $commentDB['creationDate'], $commentDB['is_reported']);
    }

    public function postComment($articleId, $pseudonyme, $content)
    {
        $comments = $this->db->prepare('INSERT INTO comments(article_id, pseudonyme, comments, creationDate) VALUES(?, ?, ?, NOW())');

        $affectedLines = $comments->execute(array($articleId, $pseudonyme, $content));

        return $affectedLines;
    }

    public function getCommentsByArticle($articleId)
    {
        $commentsdb = $this->db->query('SELECT * FROM comments WHERE article_id = ' . $articleId)->fetchAll();
        $comments = array();
        foreach ($commentsdb as $comment) {
            array_push($comments, new Comment($comment['id'], $comment['article_id'], $comment['pseudonyme'], $comment['comments'], $comment['creationDate'], $comment["is_reported"]));
        }
        return $comments;
    }

    public function reportComment($commentId)
    {
        return $this->db
            ->query('UPDATE comments SET is_reported = 1 WHERE id = ' . $commentId)
            ->execute();
    }

    public function getReportedComments($articleId)
    {
        $commentsDB = $this->db
            ->query('SELECT * FROM comments WHERE is_reported = 1 AND article_id = ' . $articleId)
            ->fetchAll();
        $comments = array();
        foreach ($commentsDB as $commentDB) {
            array_push($comments, new Comment($commentDB["id"], $commentDB["article_id"], $commentDB["pseudonyme"], $commentDB["comments"], $commentDB["creationDate"], $commentDB["is_reported"]));
        }
        return $comments;

    }

    public function delete($commentId)
    {
        return $this->db->query('DELETE FROM comments WHERE id = ' . $commentId)->execute();
    }

    public function deleteAllFromArticleId($articleId)
    {
        return $this->db->query('DELETE FROM comments WHERE article_id = ' . $articleId);
    }

}
