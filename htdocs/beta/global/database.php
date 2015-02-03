<?php

  class Database {
    public static function get() {
      static $instance = null;
      if (null === $instance) {
        try {
          try {
            $instance = new PDO('mysql:host='.DATABASE_HOST.';port='.DATABASE_PORT.';dbname='.DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
          } catch (PDOException $e) {
            $instance = new PDO('mysql:host='.DATABASE_HOST.':'.DATABASE_PORT.';dbname='.DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
          }
        } catch (PDOException $e) {
          print "Error : " . $e->getMessage() . "<br/>";
          die();
        }
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
