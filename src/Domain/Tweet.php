<?php

namespace Domain;

class Tweet
{
    private $id;
    private $link;
    private $content;
    private $user;
    private $date;

    /**
     * Tweet constructor.
     * @param $id
     * @param $link
     * @param $content
     * @param $user
     * @param $date
     */
    public function __construct($link, $content, $user, $date, $id = null)
    {
        $this->id = $id;
        $this->link = $link;
        $this->content = $content;
        $this->user = $user;
        $this->date = $date;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }




}