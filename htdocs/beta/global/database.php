<?php

  class Database {
    public static function get() {
      static $instance = null;
      if (null === $instance) {
        try {
          try {
            $port = DATABASE_PORT == "" ? "" : ';port='.DATABASE_PORT;
            $instance = new PDO('mysql:host='.DATABASE_HOST.$port.';dbname='.DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
          } catch (PDOException $e) {
            $port = DATABASE_PORT == "" ? "" : ':'.DATABASE_PORT;
            $instance = new PDO('mysql:host='.DATABASE_HOST.$port.';dbname='.DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
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
