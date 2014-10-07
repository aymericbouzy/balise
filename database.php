<?php

  class Database {
    public static function get() {
      static $instance = null;
      if (null === $instance) {
        $instance = new PDO('mysql:host=localhost;dbname=Balise', 'root', '');
      }

      return $instance;
    }

    protected function __construct() {
    }

    private function __clone() {
    }

    private function __wakeup() {
    }
  }
