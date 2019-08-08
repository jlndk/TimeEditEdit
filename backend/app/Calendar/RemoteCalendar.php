<?php

namespace App\Calendar;

use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class RemoteCalendar implements \Serializable
{
    /**
     * @var string|null
     */
    protected $url;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct(?string $url = null, Client $client)
    {
        $this->url = $url;
        $this->client = $client;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
        return $this;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    public function fetch() : StreamInterface
    {
        $res = $this->client->request('GET', $this->url);

        //Since TimeEdit sucks we need to look at the content type to decide if we got an valid calendar or not
        if ($res->getHeaderLine('content-type') == "text/html; charset=UTF-8" || $res->getStatusCode() == 404) {
            abort(404);
        }

        return $res->getBody();
    }

    public function serialize()
    {
        return serialize([
            'url' => $this->url
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->url = $data['url'];
    }
}
