<?php

namespace Ingewikkeld\LinkTuesdayBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
//use Symfony\Bundle\FrameworkBundle\Util\Mustache;

use ZendService\Twitter\Twitter as ZendTwitter;

use Ingewikkeld\LinkTuesdayBundle\Entity\Link;
use Ingewikkeld\LinkTuesdayBundle\Entity\Tweet;

/**
 * Fetches new tweets from twitter
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class FetchTweetCommand extends Command
{
    protected $container;

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                ))
            ->setHelp(<<<EOT
The <info>linktuesday:fetchtweet</info> command fetches and parses new tweets looking for new links to index

<info>./app/console linktuesday:fetchtweet</info>
EOT
            )
            ->setName('linktuesday:fetchtweet')
        ;
    }

    /**
     * @see Command
     *
     * @throws \InvalidArgumentException When namespace doesn't end with Bundle
     * @throws \RuntimeException         When bundle can't be executed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $search = new ZendTwitter(array(
            'accessToken' => array(
                'token' => $this->getContainer()->getParameter('twitter_token'),
                'secret' => $this->getContainer()->getParameter('twitter_secret'),
            ),
            'oauth_options' => array(
                'consumerKey' => $this->getContainer()->getParameter('twitter_consumerkey'),
                'consumerSecret' => $this->getContainer()->getParameter('twitter_consumersecret'),
            ),
            'http_client_options' => array(
                'adapter' => 'Zend\Http\Client\Adapter\Curl',
            ),
        ));

        $curl = new \Zend\Http\Client\Adapter\Curl();
        $curl->setCurlOption(CURLOPT_SSL_VERIFYHOST, false);
        $curl->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);

        $search->getHttpClient()->setAdapter($curl);

        $search->account->verifyCredentials();

        $results = $search->search->tweets('#linktuesday', array('lang' => 'en'));
        $results = json_decode($results->getRawResponse());

        foreach($results->statuses as $result)
        {
            $uri = '';
            $parts = explode(' ', $result->text);
            foreach($parts as $part)
            {
                if (substr($part, 0, 7) == 'http://')
                {
                    $uri = $part;
                }
            }

            if (!empty($uri))
            {

                $link = new Link();
                $link->setUri($uri);

                $existing = $em->getRepository('IngewikkeldLinkTuesdayBundle:Link')->getByFullUri($link->getFullUri());
                if ($existing)
                {
                    $link = $existing;
                }

                if ($link->getId() < 1)
                {
                    $em->persist($link);
                }

                $tweetDate = new \DateTime($result->created_at);

                $existingTweet = $em->getRepository('IngewikkeldLinkTuesdayBundle:Tweet')->findOneBy(array(
                    'date' => $tweetDate,
                    'user' => $result->user->screen_name,
                    'content' => $result->text,
                ));

                if (!$existingTweet)
                {
                    $tweet = new Tweet();
                    $tweet->setContent($result->text);
                    $tweet->setLink($link);
                    $tweet->setDate(new \DateTime($result->created_at));
                    $tweet->setProfileImage($result->user->profile_image_url);
                    $tweet->setUser($result->user->screen_name);
                    $tweet->setUri('test');

                    $em->persist($tweet);
                }
                
                $em->flush();
            }
        }
    }
}
