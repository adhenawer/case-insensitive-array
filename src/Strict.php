<?php

namespace Ayesh\CaseInsensitiveArray;

class Strict implements \Iterator, \ArrayAccess, \Countable   {
  protected $container = [];

  protected function getHash($key) {
    return \strtolower($key);
  }

  /**
   * Hash the initial array, and store in the container.
   *
   * @param mixed[mixed] Optional, an array to start with.
   */
  public function __construct(array $array = []) {
    $this->container = [];

    foreach ($array as $key => $value) {
      $this->offsetSet($key, $value);
    }
  }

  /**
   * @param mixed $offset
   * @return bool
   */
  public function offsetExists($offset) {
    return isset($this->container[ $this->getHash($offset) ]);
  }

  /**
   * @param mixed $offset
   */
  public function offsetUnset($offset) {
    $hash = $this->getHash($offset);
    unset($this->container[$hash]);
  }

  /**
   * @param mixed $offset
   * @return mixed
   */
  public function offsetGet($offset) {
    $hash = $this->getHash($offset);
    if (isset($this->container[$hash])) {
      return $this->container[$hash][0];
    }

    return NULL;
  }


  /**
   * Store the given key and value into the container, and its hash to the
   * hashes list.
   * @param mixed $offset
   * @param mixed $value
   */
  public function offsetSet($offset, $value) {
    if (NULL === $offset) {
      $this->container[] = [$value, NULL];
      return;
    }
    $this->container[$this->getHash($offset)] = [$value, $offset];
  }

  public function count() {
    return \count($this->container);
  }




  public function valid() {
    $key = $this->key();
    return isset($this->container[$this->getHash($key)][0]);
  }

  public function current() {
    $subset = \current($this->container);
    return $subset[0];
  }

  /**
   * @return mixed
   */
  public function key() {
    $subset = \current($this->container);
    if (NULL === $subset[1]) {
      return key($this->container);
    }
    return $subset[1];
  }

  public function rewind() {
    return \reset($this->container);
  }

  public function next() {
    return \next($this->container);
  }

  public function __debugInfo() {
    $return = array();
    foreach ($this->container as $container) {
      if (isset($container[1])) {
        $return[$container[1]] = $container[0];
      }
      else {
        $return[] = $container[0];
      }
    }

    return $return;
  }

}
