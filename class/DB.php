<?php

namespace Wf3;

use PDO;
use PDOException;

class DB
{

  static private $db_host = 'localhost';
  static private $db_name = 'POO_blog';
  static private $db_user = 'wf3';
  static private $db_password = '1234';

  /**
   * Retourne une instance de connexion à la base de données.
   *
   * @return PDO
   */
  static public function  connect():PDO
  {
    // Tentative de connexion à MySQL.
    try {
      return new PDO('mysql:host=' . self::$db_host . ';dbname=' . self::$db_name, self::$db_user, self::$db_password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }
    //Si erreur on affiche le message issu de l'exception et on arrête tout.
    catch (PDOException $e) {
      print "Erreur !: " . $e->getMessage() . "<br/>";
      die();
    }
  }
}
