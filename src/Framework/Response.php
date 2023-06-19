<?php

namespace App\Framework;

class Response
{
    protected $content;
    protected $headers;

    public function __construct($content = null, $headers = [])
    {
        $this->content = $content;
        $this->headers = $headers;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function send()
    {
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->content;
    }
}