<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace rootedit\core;

/**
 * Description of Uri
 *
 * @author Romain Flacher <romain.flacher at gmail.com>
 */
class Uri implements \Psr\Http\Message\UriInterface {

	private $uri;
	private $path;

	public function __construct($uri = null, $relativeBase = '') {
		$this->uri = ($uri == null) ? $_SERVER['REQUEST_URI'] : $uri;
		\sscanf($uri, $relativeBase . '%[^?][/$]', $this->path);
		$this->path = \rtrim($this->path, '/') . '/';
		//define('RELATIVE_BASE_URL', $url) && define('ABSOLUTE_BASE_URL', (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . RELATIVE_BASE_URL);
	}

	public function __toString() {
		throw new Exception('not implemented');
	}

	public function getAuthority() {
		throw new Exception('not implemented');
	}

	public function getFragment() {
		throw new Exception('not implemented');
	}

	public function getHost() {
		throw new Exception('not implemented');
	}

	public function getPath() {
		return $this->path;
	}

	public function getPort() {
		throw new Exception('not implemented');
	}

	public function getQuery() {
		if ($this->query == null)
			$this->query = $_SERVER['QUERY_STRING'];
		return $this->query;
	}

	public function getScheme() {
		throw new Exception('not implemented');
	}

	public function getUserInfo() {
		throw new Exception('not implemented');
	}

	public function withFragment($fragment) {
		throw new Exception('not implemented');
	}

	public function withHost($host) {
		throw new Exception('not implemented');
	}

	public function withPath($path) {
		$this->path = $path;
		return $this;
	}

	public function withPort($port) {
		throw new Exception('not implemented');
	}

	public function withQuery($query) {
		throw new Exception('not implemented');
	}

	public function withScheme($scheme) {
		throw new Exception('not implemented');
	}

	public function withUserInfo($user, $password = null) {
		throw new Exception('not implemented');
	}

}
