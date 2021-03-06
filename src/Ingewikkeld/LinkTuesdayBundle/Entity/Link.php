<?php

namespace Ingewikkeld\LinkTuesdayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Buzz\Browser;

/**
 * @ORM\Table(name="Link")
 * @ORM\Entity(repositoryClass="Ingewikkeld\LinkTuesdayBundle\Entity\LinkRepository")
 */
class Link
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $uri;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $full_uri;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\Ingewikkeld\LinkTuesdayBundle\Entity\Tweet", mappedBy="link")
     * @ORM\OrderBy({"date" = "ASC"})
     */
    protected $tweets;

    /**
     * Constructor. Sets up the tweets property as a new ArrayCollection
     */
    public function __construct()
    {
        $this->tweets = new ArrayCollection();
    }

    /**
     * Get the id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id
     *
     * @param int $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setUri($uri)
    {
        $this->uri = trim($uri);
    }

    public function getFullUri()
    {
        return $this->full_uri;
    }

    public function setFullUri($full_uri)
    {
        $this->full_uri = $full_uri;
        $this->followUri();
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTweets()
    {
        return $this->tweets;
    }

    public function getFirstTweet()
    {
      $tweets = $this->getTweets();
      return $tweets[0];
    }

    public function followUri()
    {
        $browser = new Browser();
        try {
            $response = $browser->get($this->getFullUri());

            $dom = $response->toDomDocument();
            $sxml = simplexml_import_dom($dom);

            $titles = $sxml->xpath('//title');
        } catch(\Exception $e) {
            $titles = array();
        }

        if (count($titles) > 0)
        {
            $title = (string) $titles[0];
        }
        else
        {
            $title = $this->getFullUri();
        }
        $this->setTitle(trim($title));
    }

    /**
     * Get only the domain part out of the full uri
     *
     * @return string
     */
    public function getDomain()
    {
        $fullUri = $this->getFullUri();
        $parts = explode('/', $fullUri);
        if (isset($parts[2])) {
            return $parts[2];
        }
    }
}