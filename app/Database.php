<?php
final class Database {
  private static ?PDO $pdo = null;
  public static function pdo(): PDO {
    if (!self::$pdo) {
      $c = (require __DIR__.'/config.php')['db'];
      self::$pdo = new PDO("mysql:host={$c['host']};dbname={$c['name']};charset=utf8mb4",$c['user'],$c['pass'],
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
    }
    return self::$pdo;
  }
  public static function all(string $sql, array $p = []): array { $s=self::pdo()->prepare($sql); $s->execute($p); return $s->fetchAll(); }
  public static function run(string $sql, array $p = []): bool { return self::pdo()->prepare($sql)->execute($p); }
}
