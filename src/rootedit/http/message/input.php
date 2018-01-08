<?php

namespace rootedit\core;

class Input extends HttpMessage implements \Psr\Http\Message\ServerRequestInterface {

	private $uri;


public function getAttribute($name, $default = null) {
    return isset($this->attributtes[$name]) ? $this->attributtes[$name] : $default;
}

public function getAttributes() {
    return $this->attributtes;
}

public function getCookieParams() {
    return $_COOKIE;
}

public function getMethod() {
    return $_SERVER['REQUEST_METHOD'];
}

public function getParsedBody() {
    if (isset($_SERVER['CONTENT_TYPE'])) {
        $type = $_SERVER['CONTENT_TYPE'];
        if ($type == 'application/x-www-form-urlencoded' || $type == 'multipart/form-data') {
            return $_POST;
        }
    }
    if (($json = json_decode(stream_get_contents(STDIN))) !== null) {
        return $json;
    }
}

public function getQueryParams() {
    parse_str($_SERVER['QUERY_STRING'], $arr);
    return $arr;
}

public function getRequestTarget() {
    throw new Exception('not implemented');
}

public function getServerParams() {
    throw new Exception('not implemented');
}

public function getUploadedFiles() {
    throw new Exception('not implemented');
}

public function getUri() {
	if($this->uri == null) $this->uri =new Uri();
    return $this->uri;
}

public function withAttribute($name, $value) {
    throw new Exception('not implemented');
}

public function withCookieParams(array $cookies) {
    throw new Exception('not implemented');
}

public function withMethod($method) {
    throw new Exception('not implemented');
}

public function withParsedBody($data) {
    throw new Exception('not implemented');
}

public function withQueryParams(array $query) {
    throw new Exception('not implemented');
}

public function withRequestTarget($requestTarget) {
    throw new Exception('not implemented');
}

public function withUploadedFiles(array $uploadedFiles) {
    throw new Exception('not implemented');
}

public function withUri(\Psr\Http\Message\UriInterface $uri, $preserveHost = false) {
    $this->uri = $uri ;
	return $this;
}

public function withoutAttribute($name) {
    throw new Exception('not implemented');
}

}
