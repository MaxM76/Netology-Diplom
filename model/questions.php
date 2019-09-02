<?php

namespace localhost\model;

//use localhost\controller\PrimaryController;

/**
 * Class Questions
 * @package localhost\model
 */
class Questions extends Model
{
    /**
     * @param array $params
     * @return bool
     */
    public function add($params)
    {
        $sth = $this->getDatabase()->prepare('
            INSERT
            INTO
                `questions` (`text`, `topic_id`, `author_id`, `status`, `is_published`)
            VALUES
                (:text, :topic_id, :author, :status, :published)
        ');

        $sth->bindValue(':text', $params['text'], \PDO::PARAM_STR);
        $sth->bindValue(':topic_id', $params['topic_id'], \PDO::PARAM_INT);
        $sth->bindValue(':author', $params['author'], \PDO::PARAM_INT);
        $sth->bindValue(':status', $params['status'], \PDO::PARAM_INT);
        $sth->bindValue(':published', QUESTION_NOT_PUBLISHED, \PDO::PARAM_INT);

        $result = $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $id
     * @return int
     */
    public function getAnswersCount($id)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                COUNT(*) AS `total`
            FROM
                `answers`
            WHERE
                `question_id`=:id
        ');

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        if ($sth->execute()) {
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
        } else {
            $this->lastPDOError = $sth->errorInfo();
            return -1;
        }
        return $result['total'];
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteAnswersOfQuestion($id)
    {
/*        $sth = $this->getDatabase()->prepare('
            SELECT
                COUNT(*) AS `total`
            FROM
                `answers`
            WHERE
                `question_id`=:id
        ');

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        if ($sth->execute()) {
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
        } else {
            $this->lastPDOError = $sth->errorInfo();
            return false;
        }
*/
        if ($this->getAnswersCount($id) == 0) {
            return true;
        }

        $sth = $this->getDatabase()->prepare('
            DELETE
            FROM
                `answers`
            WHERE
                `question_id`=:id
        ');

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $success = $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        return $success;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        if ($this->deleteAnswersOfQuestion($id)) {
            $sth = $this->getDatabase()->prepare('
                DELETE
                FROM
                    `questions`
                WHERE
                    `id`=:id
            ');

            $sth->bindValue(':id', $id, \PDO::PARAM_INT);
            $result = $sth->execute();
            $this->lastPDOError = $sth->errorInfo();
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update($id, $params)
    {
        if (count($params) == 0) {
            return false;
        }
        $update = [];
        foreach ($params as $param => $value) {
            $update[] = '`'.$param.'`=:'.$param;
        }
        $query = '
            UPDATE
                `questions`
            SET '
            .implode(', ', $update).'
            WHERE
                `id`=:id
        ';
        $sth = $this->getDatabase()->prepare($query);

        if (isset($params['text'])) {
            $sth->bindValue(':text', $params['text'], \PDO::PARAM_STR);
        }

        if (isset($params['topic_id'])) {
            $sth->bindValue(':topic_id', $params['topic_id'], \PDO::PARAM_INT);
        }

        if (isset($params['is_published'])) {
            $sth->bindValue(':is_published', $params['is_published'], \PDO::PARAM_INT);
        }

        if (isset($params['status'])) {
            $sth->bindValue(':status', $params['status'], \PDO::PARAM_INT);
        }
    
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);

        $result = $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getItem($id)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                `id`,
                `text`,
                `topic_id`,
                `created_at`,
                `author_id`,
                `status`,
                `is_published`
            FROM
                `questions`
            WHERE
                `id`=:id
        ');

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @param int $ownerId
     * @return array
     */
    public function getList($ownerId = -1)
    {
        if ($ownerId == -1) {
            return [];
        }
        $sth = $this->getDatabase()->prepare('
            SELECT
                `questions`.`id`,
                `text`,
                `topic_id`,
                `created_at`,
                `author_id`,
                `login`,
                `email`,
                `status`,
                `is_published`
            FROM
                `questions`
            INNER JOIN
                `users`
            ON
                `author_id`=`users`.`id`
            WHERE
                `topic_id`=:id
        ');

        $sth->bindValue(':id', $ownerId, \PDO::PARAM_INT);
        if ($sth->execute()) {
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $result = [];
        }
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $topicId
     * @return array
     */
    public function getPublishedList($topicId)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                `questions`.`id`,
                `text`,
                `topic_id`,
                `created_at`,
                `author_id`,
                `login`,
                `email`,
                `status`,
                `is_published`
            FROM
                `questions` 
            INNER JOIN
                `users`
            ON
                `author_id`=`users`.`id`    
            WHERE
                `topic_id`=:id AND `is_published`= true
            ');


        $sth->bindValue(':id', $topicId, \PDO::PARAM_INT);
        if ($sth->execute()) {
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $result = [];
        }
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $topicId
     * @return array
     */
    public function getUnpublishedList($topicId)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                `questions`.`id`,
                `text`,
                `topic_id`,
                `created_at`,
                `author_id`,
                `login`,
                `email`,
                `status`,
                `is_published`
            FROM
                `questions` 
            INNER JOIN
                `users`
            ON
                `author_id`=`users`.`id`    
            WHERE
                `topic_id`=:id AND `is_published`= false
            ');


        $sth->bindValue(':id', $topicId, \PDO::PARAM_INT);
        if ($sth->execute()) {
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $result = [];
        }
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $topicId
     * @return array
     */
    public function getUnansweredList($topicId)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                `questions`.`id`,
                `text`,
                `topic_id`,
                `created_at`,
                `author_id`,
                `login`,
                `email`,
                `status`,
                `is_published`
            FROM
                `questions` 
            INNER JOIN
                `users`
            ON
                `author_id`=`users`.`id`    
            WHERE
                `topic_id`=:id AND `status`= false
            ');


        $sth->bindValue(':id', $topicId, \PDO::PARAM_INT);
        if ($sth->execute()) {
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $result = [];
        }
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $id
     * @return int
     */
    public function getTopicId($id)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
               `topic_id`
            FROM
                `questions`
            WHERE
                `id`=:id
        ');

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        return $result['topic_id'];
    }


    /**
     * @param int $id
     * @return bool
     */
    public function answerQuestion($id)
    {
        return $this->update($id, ['status' => (int)QUESTION_ANSWERED]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function unanswerQuestion($id)
    {
        return $this->update($id, ['status' => (int)QUESTION_NOT_ANSWERED]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function publishQuestion($id)
    {
        return $this->update($id, ['is_published' => (int)QUESTION_PUBLISHED]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function unpublishQuestion($id)
    {
        return $this->update($id, ['is_published' => (int)QUESTION_NOT_PUBLISHED]);
    }
}
