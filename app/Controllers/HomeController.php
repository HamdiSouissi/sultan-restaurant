<?php
final class HomeController {
  public function index(): void {
    $daily = null;
    try {
      $s = array_column(Database::all('SELECT k,v FROM settings'),'v','k');
      $cats = Database::all('SELECT * FROM categories ORDER BY sort');
      $dishes = Database::all('SELECT d.*,c.slug cat FROM dishes d JOIN categories c ON c.id=d.category_id WHERE available=1 ORDER BY c.sort,d.id');
      $featured = array_values(array_filter($dishes, fn($d)=>$d['featured']));
      $hours = Database::all('SELECT * FROM hours ORDER BY day');
      $socials = Database::all('SELECT * FROM socials');
      $reviews = Database::all('SELECT * FROM reviews WHERE approved=1 ORDER BY id DESC LIMIT 9');
      $gallery = Database::all('SELECT * FROM gallery ORDER BY sort');
      $today = date('Y-m-d');
      Database::run('INSERT INTO stats(day,visits) VALUES(?,1) ON DUPLICATE KEY UPDATE visits=visits+1', [$today]);
    } catch (Throwable $e) { http_response_code(500); exit('Database non disponibile: importate sql/schema.sql e controllate app/config.php.'); }

    // Il piatto del giorno è facoltativo: un database non migrato non deve rompere il sito.
    try {
      $daily = Database::all('SELECT * FROM daily_special WHERE active = 1 ORDER BY served_on DESC, id LIMIT 1')[0] ?? null;
    } catch (Throwable $e) { $daily = null; }

    require __DIR__.'/../views/home.php';
  }
}
