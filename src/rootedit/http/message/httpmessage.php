<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace rootedit\core;

/**
 * Description of HttpMessage
 *
 * @author romai
 */
class HttpMessage implements \Psr\Http\Message\MessageInterface {

    private $body;

    public function getBody() {
        return $this->body;
    }

    public function getHeader($name) {
        return $this->hasHeader($name) ? $this->headers[$this->loweredHeaders[mb_strtolower($name)]] : [];
    }

    public function getHeaderLine($name) {
        return implode(", ", $this->getHeader($name));
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getProtocolVersion() {
        return $this->version;
    }

    public function hasHeader($name) {
        return isset($this->loweredHeaders[mb_strtolower($name)]);
    }

    public function withAddedHeader($name, $value) {
        $old = $this->getHeader($name);
        $this->withoutHeader($name);
        $this->loweredHeaders[mb_strtolower($name)] = $name;
        $this->headers[$name] = array_merge($old, $value);
        return $this;
    }

    public function withBody(\Psr\Http\Message\StreamInterface $body) {
        $this->body = $body;
        return $this;
    }

    public function withHeader($name, $value) {
        $this->withoutHeader($name);
        $this->loweredHeaders[mb_strtolower($name)] = $name;
        $this->headers[$name] = $value;
        return $this;
    }

    public function withProtocolVersion($version) {
        $this->version = $version;
        return $this;
    }

    public function withoutHeader($name) {
        if ($this->hasHeader($name)) {
            unset($this->headers[$this->loweredHeaders[mb_strtolower($name)]]);
        }
        return $this;
    }

}
