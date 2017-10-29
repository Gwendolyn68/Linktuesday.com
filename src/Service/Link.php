<?php

namespace Service;

class Link
{
  private $db;
  private $tweetService;

    /**
     * Link constructor.
     * @param \mysqli $db
     */
    public function __construct(\mysqli $db, Tweet $tweetService)
    {
        $this->db = $db;
        $this->tweetService = $tweetService;
    }

    public function getMostRecentLinks()
    {
        $result = $this->db->query("SELECT * FROM Link ORDER BY id DESC LIMIT 10");

        $links = array();

        foreach($result->fetch_all(MYSQLI_ASSOC) as $item){
            $link = new \Domain\Link(
                $item['uri'],
                $item['full_uri'],
                $item['title'],
                $item['description'],
                $item['id']
            );
            $tweets = $this->tweetService->getTweetsForLink($link);
            $link->setTweets($tweets);
            $links[] = $link;
        }

        return $links;
    }


}