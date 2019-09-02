<?php

namespace localhost\model;

/**
 * Class Topics
 * @package localhost\model
 */
class Topics extends Model
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
                `topics` (`text`, `description`, `author_id`)
            VALUES
                (:text, :description, :author)
        ');
        $sth->bindValue(':text', $params['text'], \PDO::PARAM_STR);
        $sth->bindValue(':description', $params['description'], \PDO::PARAM_STR);
        $sth->bindValue(':author', $params['author'], \PDO::PARAM_INT);
        $result = $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $topicId
     * @return int
     */
    protected function getQuestionsCount($topicId)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                COUNT(*) AS `total`
            FROM
                `questions`
            WHERE
                `id`=:id
        ');
        $sth->bindValue(':id', $topicId, \PDO::PARAM_INT);

        if ($sth->execute()) {
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
        } else {
            $this->lastPDOError = $sth->errorInfo();
            return -1;
        }
        return $result['total'];
    }

    /**
     * @param int $topicId
     * @return bool
     */
    public function deleteQuestionsOfTopic($topicId)
    {
/*        $sth = $this->getDatabase()->prepare('
            SELECT
                COUNT(*) AS `total`
            FROM
                `questions`
            WHERE
                `id`=:id
        ');
        $sth->bindValue(':id', $topicId, \PDO::PARAM_INT);

        if ($sth->execute()) {
            $result = $sth->fetch(\PDO::FETCH_ASSOC);
        } else {
            $this->lastPDOError = $sth->errorInfo();
            return false;
        }
*/
        if ($this->getQuestionsCount($topicId) == 0) {
            return true;
        }

        $sth = $this->getDatabase()->prepare('
            SELECT
                `id`
            FROM
                `questions` 
            WHERE
                `topic_id`=:id
        ');

        $sth->bindValue(':id', $topicId, \PDO::PARAM_INT);
        $result = $sth->execute();
        $this->lastPDOError = $sth->errorInfo();

        if ($result) {
            $questionsList = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }

        if ($questionsList) {
            foreach ($questionsList as $question) {
                $result = $result && $this->deleteQuestion($question['id']);
            }
        }
        return $result;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteQuestion($id)
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
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * @param int $id
     * @return bool
     */
    protected function getAnswersCount($id)
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
            return false;
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
        if (!($this->deleteQuestionsOfTopic($id))) {
            return false;
        }

        $sth = $this->getDatabase()->prepare('
            DELETE
            FROM
                `topics`
            WHERE
                `id`=:id
        ');

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $result = $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        return $result;
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
            $update[] = $param.'`=:'.$param;
        }

        $sth = $this->getDatabase()->prepare('
            UPDATE
                `topics`
            SET '
                .implode(', `', $update).'
            WHERE
                `id`=:id
        ');

        if (isset($params['text'])) {
            $sth->bindValue(':text', $params['text'], \PDO::PARAM_STR);
        }

        if (isset($params['description'])) {
            $sth->bindValue(':description', $params['description'], \PDO::PARAM_STR);
        }
        
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);

        $result = $sth->execute();
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $ownerId
     * @return array
     */
    public function getList($ownerId = -1)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                `topics`.`id`,
                `topics`.`text`,
                `topics`.`description`,
                `topics`.`created_at`,
                COUNT(*) AS `total`,
                SUM(`questions`.`is_published`) AS `published`,
                SUM(`questions`.`status`) AS `answered`
            FROM
                `topics`,
                `questions`
            WHERE
                `questions`.`topic_id` = `topics`.`id`
            GROUP BY
                `topics`.`id`
        ');

        if ($sth->execute()) {
            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $result = [];
            $this->lastPDOError = $sth->errorInfo();
            return $result;
        }

        $sth = $this->getDatabase()->prepare('
            SELECT
                `topics`.`id`,
                `text`,
                `description`,
                `created_at`,
                0 AS `total`,
                0 AS `published`,
                0 AS `answered`
            FROM
                `topics`
            WHERE
                `id` NOT IN (
            SELECT
                `topic_id`
            FROM
                `questions`
            )
        ');

        if ($sth->execute()) {
            //array_splice($result, 0, 0, $sth->fetchAll(\PDO::FETCH_ASSOC));
            $result = array_merge($result, $sth->fetchAll(\PDO::FETCH_ASSOC));
        } else {
            $result = [];
            $this->lastPDOError = $sth->errorInfo();
            return $result;
        }
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getItem($id)
    {
        $sth = $this->getDatabase()->prepare('
            SELECT
                `id`, `text`, `description`, `created_at`
            FROM
                `topics`
            WHERE
                `id`=:id
        ');

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(\PDO::FETCH_ASSOC);
        $this->lastPDOError = $sth->errorInfo();
        return $result;
    }
}
