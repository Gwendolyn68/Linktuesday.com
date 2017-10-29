<?php

namespace Service;


class Tweet
{
    private $db;

    /**
     * Tweet constructor.
     * @param $db
     */
    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getTweetsForLink(\Domain\Link $link)
    {
       $linkId = $link->getId();

        $result = $this->db->query("SELECT * FROM Tweet WHERE link_id = ".(int)$linkId." ORDER BY id ASC");

        $tweets = array();

        foreach($result->fetch_all(MYSQLI_ASSOC) as $item) {
            $tweet = new \Domain\Tweet(
                $link,
                $item['content'],
                $item['user'],
                $item ['date'],
                $item ['id']
            );
            $tweets[] = $tweet;
        }

        return $tweets;
    }
}