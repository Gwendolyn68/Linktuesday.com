<?php

namespace Domain;

class Link
{
    private $id;
    private $uri;
    private $fullUri;
    private $title;
    private $description;
    private $tweets;

    /**
     * Link constructor.
     * @param $id
     * @param $uri
     * @param $fullUri
     * @param $title
     * @param $description
     */
    public function __construct($uri, $fullUri, $title, $description, $id = null)
    {
        $this->id = $id;
        $this->uri = $uri;
        $this->fullUri = $fullUri;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @param mixed $tweets
     */
    public function setTweets($tweets)
    {
        $this->tweets = $tweets;
    }

    /**
     * @return mixed
     */
    public function getTweets()
    {
        return $this->tweets;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function getFullUri()
    {
        return $this->fullUri;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }


}