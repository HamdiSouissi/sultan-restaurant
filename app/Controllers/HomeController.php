<?php
final class HomeController {
  public function index(): void {
    try {
      $s = array_column(Database::all('SELECT k,v FROM settings'),'v','k');
      $cats = Database::all('SELECT * FROM categories ORDER BY sort');
      $dishes = Database::all('SELECT d.*,c.slug cat FROM dishes d JOIN categories c ON c.id=d.category_id WHERE available=1 ORDER BY c.sort,d.id');
      $featured = array_values(array_filter($dishes, fn($d)=>$d['featured']));
      $hours = Database::all('SELECT * FROM hours ORDER BY day');
      $socials = Database::all('SELECT * FROM socials');
      $reviews = Database::all('SELECT * FROM reviews WHERE approved=1 ORDER BY id DESC LIMIT 9');
      $gallery = Database::all('SELECT * FROM gallery ORDER BY sort');
      Database::run('INSERT INTO stats(day,visits) VALUES(CURDATE(),1) ON DUPLICATE KEY UPDATE visits=visits+1');
    } catch (Throwable $e) { http_response_code(500); exit('Base de données indisponible : importez sql/schema.sql et vérifiez app/config.php.'); }
    require __DIR__.'/../views/home.php';
  }
}
