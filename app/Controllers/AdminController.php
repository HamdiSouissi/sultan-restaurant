<?php
/**
 * Espace d'administration du Sultan.
 * Authentification, tableau de bord, carte, catégories, plat du jour,
 * informations de la maison, galerie, demandes, avis et compte.
 */
final class AdminController {

  private const SETTING_KEYS = [
    'name', 'tagline', 'description', 'about_text',
    'phone', 'whatsapp', 'email', 'address',
    'lat', 'lng', 'maps_embed', 'menu_note', 'quote_text',
  ];

  private const MAX_LOGIN_ATTEMPTS = 8;

  /* ================================================================
   *  Routage
   * ================================================================ */
  public function dispatch(string $path): void {
    $path = '/'.trim($path, '/');
    $post = strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET')) === 'POST';
    header('X-Robots-Tag: noindex, nofollow');

    try {
      $admins = (int)(Database::all('SELECT COUNT(*) AS c FROM admins')[0]['c'] ?? 0);
    } catch (Throwable $ex) {
      $this->dbIssue($ex);
      return;
    }

    if ($admins === 0) {
      if ($path === '/admin/install') {
        $post ? $this->install() : $this->view('admin/install', [], 'Première installation', 'guest');
        return;
      }
      redirect('/admin/install');
    }
    if ($path === '/admin/install') redirect('/admin/login');

    if ($path === '/admin/login') {
      if ($this->current()) redirect('/admin');
      $post ? $this->login() : $this->view('admin/login', [], 'Connexion', 'guest');
      return;
    }
    if ($path === '/admin/logout') { $this->logout(); return; }

    if (!$this->current()) redirect('/admin/login');

    if ($path === '/admin/export/newsletter') { $this->exportNewsletter(); return; }

    switch ($path) {
      case '/admin': $this->dashboard(); return;
      case '/admin/carte': $this->carte(); return;
      case '/admin/categories': $this->categories(); return;
      case '/admin/plat-du-jour': $this->daily(); return;
      case '/admin/infos': $this->infos(); return;
      case '/admin/galerie': $this->gallery(); return;
      case '/admin/demandes': $this->requests(); return;
      case '/admin/avis': $this->reviews(); return;
      case '/admin/compte': $this->account(); return;

      /* Actions (POST) */
      case '/admin/carte/enregistrer': $this->saveDish(); return;
      case '/admin/carte/supprimer': $this->deleteDish(); return;
      case '/admin/carte/basculer': $this->toggleDish(); return;
      case '/admin/categories/enregistrer': $this->saveCategory(); return;
      case '/admin/categories/supprimer': $this->deleteCategory(); return;
      case '/admin/categories/deplacer': $this->moveCategory(); return;
      case '/admin/plat-du-jour/enregistrer': $this->saveDaily(); return;
      case '/admin/plat-du-jour/retirer': $this->retireDaily(); return;
      case '/admin/infos/enregistrer': $this->saveSettings(); return;
      case '/admin/horaires/enregistrer': $this->saveHours(); return;
      case '/admin/reseaux/enregistrer': $this->saveSocial(); return;
      case '/admin/reseaux/supprimer': $this->deleteSocial(); return;
      case '/admin/galerie/enregistrer': $this->saveGallery(); return;
      case '/admin/galerie/supprimer': $this->deleteGallery(); return;
      case '/admin/demandes/statut': $this->reservationStatus(); return;
      case '/admin/demandes/supprimer': $this->deleteRequest(); return;
      case '/admin/avis/enregistrer': $this->saveReview(); return;
      case '/admin/avis/supprimer': $this->deleteReview(); return;
      case '/admin/compte/enregistrer': $this->saveAccount(); return;
    }

    http_response_code(404);
    $this->view('admin/introuvable', [], 'Page introuvable');
  }

  /* ================================================================
   *  Session & authentification
   * ================================================================ */
  private function current(): ?array {
    $id = (int)($_SESSION['admin_id'] ?? 0);
    if ($id <= 0) return null;
    $row = Database::all('SELECT id, email, password_hash FROM admins WHERE id = ? LIMIT 1', [$id])[0] ?? null;
    if (!$row) { unset($_SESSION['admin_id']); return null; }
    return $row;
  }

  private function login(): void {
    if (!csrf_ok()) { flash('err', 'Session expirée, merci de réessayer.'); redirect('/admin/login'); }
    $email = mb_strtolower(mb_substr(trim((string)($_POST['email'] ?? '')), 0, 150));
    $pass  = (string)($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $pass === '') {
      flash('err', 'Renseignez votre adresse e-mail et votre mot de passe.');
      redirect('/admin/login');
    }
    if ($this->tooManyAttempts($email)) {
      flash('err', 'Trop de tentatives. Patientez quinze minutes avant de réessayer.');
      redirect('/admin/login');
    }

    $admin = Database::all('SELECT * FROM admins WHERE email = ? LIMIT 1', [$email])[0] ?? null;
    if (!$admin || !password_verify($pass, (string)$admin['password_hash'])) {
      $this->logAttempt($email);
      usleep(250000);
      flash('err', 'E-mail ou mot de passe incorrect.');
      redirect('/admin/login');
    }

    if (password_needs_rehash((string)$admin['password_hash'], PASSWORD_DEFAULT)) {
      Database::run('UPDATE admins SET password_hash = ? WHERE id = ?',
        [password_hash($pass, PASSWORD_DEFAULT), $admin['id']]);
    }
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int)$admin['id'];
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
    flash('ok', 'Bienvenue dans votre espace de gestion.');
    redirect('/admin');
  }

  private function logout(): void {
    if (strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET')) !== 'POST' || !csrf_ok()) {
      redirect('/admin');
    }
    unset($_SESSION['admin_id']);
    session_regenerate_id(true);
    flash('ok', 'Vous êtes déconnecté.');
    redirect('/admin/login');
  }

  private function install(): void {
    if (!csrf_ok()) { flash('err', 'Session expirée, merci de réessayer.'); redirect('/admin/install'); }
    if ((int)(Database::all('SELECT COUNT(*) AS c FROM admins')[0]['c'] ?? 0) > 0) redirect('/admin/login');

    $email = mb_strtolower(mb_substr(trim((string)($_POST['email'] ?? '')), 0, 150));
    $p1 = (string)($_POST['password'] ?? '');
    $p2 = (string)($_POST['password2'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      keep_old(['email' => $email]);
      flash('err', 'Adresse e-mail invalide.');
      redirect('/admin/install');
    }
    if (strlen($p1) < 10) {
      keep_old(['email' => $email]);
      flash('err', 'Choisissez un mot de passe de 10 caractères minimum.');
      redirect('/admin/install');
    }
    if (!hash_equals($p1, $p2)) {
      keep_old(['email' => $email]);
      flash('err', 'Les deux mots de passe ne correspondent pas.');
      redirect('/admin/install');
    }

    try {
      Database::run('INSERT INTO admins(email, password_hash) VALUES(?, ?)',
        [$email, password_hash($p1, PASSWORD_DEFAULT)]);
      $id = (int)(Database::all('SELECT id FROM admins WHERE email = ? LIMIT 1', [$email])[0]['id'] ?? 0);
    } catch (Throwable $ex) {
      flash('err', 'Création impossible : '.$ex->getMessage());
      redirect('/admin/install');
    }

    forget_old();
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $id;
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
    flash('ok', 'Compte administrateur créé. Bienvenue au Sultan.');
    redirect('/admin');
  }

  private function tooManyAttempts(string $email): bool {
    try {
      $since = date('Y-m-d H:i:s', time() - 900);
      $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
      $n = (int)(Database::all(
        'SELECT COUNT(*) AS c FROM login_attempts WHERE created > ? AND (email = ? OR ip = ?)',
        [$since, $email, $ip]
      )[0]['c'] ?? 0);
      return $n >= self::MAX_LOGIN_ATTEMPTS;
    } catch (Throwable $ex) {
      return false;
    }
  }

  private function logAttempt(string $email): void {
    try {
      Database::run('INSERT INTO login_attempts(email, ip) VALUES(?, ?)',
        [$email, (string)($_SERVER['REMOTE_ADDR'] ?? '')]);
      Database::run('DELETE FROM login_attempts WHERE created < ?',
        [date('Y-m-d H:i:s', time() - 86400)]);
    } catch (Throwable $ex) { /* table absente : on ignore */ }
  }

  /* ================================================================
   *  Tableau de bord
   * ================================================================ */
  private function dashboard(): void {
    $today = date('Y-m-d');
    $stats = [
      'dishes'      => $this->scalar('SELECT COUNT(*) FROM dishes'),
      'dishes_on'   => $this->scalar('SELECT COUNT(*) FROM dishes WHERE available = 1'),
      'categories'  => $this->scalar('SELECT COUNT(*) FROM categories'),
      'res_new'     => $this->scalar("SELECT COUNT(*) FROM reservations WHERE status = 'new'"),
      'messages'    => $this->scalar('SELECT COUNT(*) FROM contacts'),
      'newsletter'  => $this->scalar('SELECT COUNT(*) FROM newsletter'),
      'reviews'     => $this->scalar('SELECT COUNT(*) FROM reviews WHERE approved = 0'),
      'visits_today'=> $this->scalar('SELECT COALESCE(SUM(visits), 0) FROM stats WHERE day = ?', [$today]),
      'visits_week' => $this->scalar('SELECT COALESCE(SUM(visits), 0) FROM stats WHERE day >= ?',
                        [date('Y-m-d', strtotime('-6 days'))]),
    ];

    $visits = Database::all('SELECT day, visits FROM stats WHERE day >= ? ORDER BY day',
      [date('Y-m-d', strtotime('-13 days'))]);
    $maxVisits = 1;
    foreach ($visits as $v) $maxVisits = max($maxVisits, (int)$v['visits']);

    $reservations = Database::all('SELECT * FROM reservations ORDER BY id DESC LIMIT 5');
    $messages     = Database::all('SELECT * FROM contacts ORDER BY id DESC LIMIT 4');
    $categories   = Database::all(
      'SELECT c.name, COUNT(d.id) AS n FROM categories c
       LEFT JOIN dishes d ON d.category_id = c.id
       GROUP BY c.id, c.name, c.sort ORDER BY c.sort, c.id');

    $daily = $this->hasTable('daily_special')
      ? (Database::all('SELECT * FROM daily_special WHERE id = 1')[0] ?? null)
      : null;

    $this->view('admin/dashboard', compact('stats', 'visits', 'maxVisits', 'reservations', 'messages', 'categories', 'daily', 'today'), 'Tableau de bord');
  }

  /* ================================================================
   *  La carte — plats
   * ================================================================ */
  private function carte(): void {
    $q    = mb_substr(trim((string)($_GET['q'] ?? '')), 0, 80);
    $cat  = (int)($_GET['cat'] ?? 0);
    $edit = (int)($_GET['edit'] ?? 0);
    $new  = isset($_GET['new']);

    $where = [];
    $params = [];
    if ($q !== '') {
      $like = '%'.$q.'%';
      $where[] = '(d.name LIKE ? OR d.description LIKE ? OR d.ingredients LIKE ?)';
      array_push($params, $like, $like, $like);
    }
    if ($cat > 0) { $where[] = 'd.category_id = ?'; $params[] = $cat; }

    $dishes = Database::all(
      'SELECT d.*, c.name AS cat_name FROM dishes d JOIN categories c ON c.id = d.category_id'
      .($where ? ' WHERE '.implode(' AND ', $where) : '')
      .' ORDER BY c.sort, c.id, d.name', $params);

    $categories = Database::all('SELECT * FROM categories ORDER BY sort, id');

    $editing = null;
    if ($edit > 0) {
      $editing = Database::all('SELECT * FROM dishes WHERE id = ? LIMIT 1', [$edit])[0] ?? null;
      if (!$editing) { flash('err', 'Ce plat n’existe plus.'); redirect('/admin/carte'); }
    } elseif (!$new) {
      $editing = null;
    }

    $this->view('admin/carte', compact('dishes', 'categories', 'editing', 'q', 'cat', 'new'), 'La carte');
  }

  private function saveDish(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $back = '/admin/carte'.($id > 0 ? '?edit='.$id : '?new=1');

    $name = $this->in('name', 120);
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $price = $this->price('price');
    $errors = [];
    if (mb_strlen($name) < 2) $errors[] = 'Indiquez le nom du plat.';
    if ($price === null) $errors[] = 'Le prix doit être un montant supérieur à 0.';
    if ($categoryId <= 0 || !Database::all('SELECT id FROM categories WHERE id = ?', [$categoryId])) {
      $errors[] = 'Choisissez une catégorie existante.';
    }
    if ($errors) { keep_old($_POST); flash('err', implode(' ', $errors)); redirect($back); }
    if (!empty($_POST['website'])) redirect($back);

    try { $upload = upload_image('image'); }
    catch (RuntimeException $ex) { keep_old($_POST); flash('err', $ex->getMessage()); redirect($back); }

    $existing = clean_path($_POST['image_path'] ?? null);
    $remove = !empty($_POST['remove_image']);
    $image = $upload ?: ($remove ? null : $existing);

    $values = [
      $categoryId, $name, $this->in('description', 255), $this->in('ingredients', 255),
      $this->in('allergens', 255), $price, $image,
      isset($_POST['featured']) ? 1 : 0, isset($_POST['available']) ? 1 : 0,
    ];

    try {
      if ($id > 0) {
        $old = Database::all('SELECT image FROM dishes WHERE id = ?', [$id])[0] ?? null;
        if (!$old) { flash('err', 'Ce plat n’existe plus.'); redirect('/admin/carte'); }
        Database::run(
          'UPDATE dishes SET category_id=?, name=?, description=?, ingredients=?, allergens=?, price=?, image=?, featured=?, available=? WHERE id=?',
          array_merge($values, [$id])
        );
        if ($old['image'] && $old['image'] !== $image) remove_upload($old['image']);
      } else {
        Database::run(
          'INSERT INTO dishes(category_id, name, description, ingredients, allergens, price, image, featured, available) VALUES(?,?,?,?,?,?,?,?,?)',
          $values
        );
      }
    } catch (Throwable $ex) {
      keep_old($_POST);
      flash('err', 'Enregistrement impossible : '.$ex->getMessage());
      redirect($back);
    }

    forget_old();
    flash('ok', $id > 0 ? '« '.$name.' » a été mis à jour.' : '« '.$name.' » a été ajouté à la carte.');
    redirect('/admin/carte');
  }

  private function deleteDish(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $row = Database::all('SELECT name, image FROM dishes WHERE id = ?', [$id])[0] ?? null;
    if (!$row) { flash('err', 'Plat introuvable.'); redirect('/admin/carte'); }
    Database::run('DELETE FROM dishes WHERE id = ?', [$id]);
    remove_upload($row['image']);
    flash('ok', '« '.$row['name'].' » a été supprimé de la carte.');
    redirect('/admin/carte');
  }

  private function toggleDish(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $field = (string)($_POST['field'] ?? '');
    if (!in_array($field, ['available', 'featured'], true)) {
      flash('err', 'Action inconnue.');
      redirect('/admin/carte');
    }
    Database::run('UPDATE dishes SET '.$field.' = 1 - '.$field.' WHERE id = ?', [$id]);
    flash('ok', $field === 'available' ? 'Disponibilité mise à jour.' : 'Mise en avant mise à jour.');
    redirect('/admin/carte');
  }

  /* ================================================================
   *  Catégories
   * ================================================================ */
  private function categories(): void {
    $categories = Database::all(
      'SELECT c.*, (SELECT COUNT(*) FROM dishes d WHERE d.category_id = c.id) AS n
       FROM categories c ORDER BY c.sort, c.id');
    $editId = (int)($_GET['edit'] ?? 0);
    $isNew = isset($_GET['new']);
    $editing = $editId > 0 ? (Database::all(
      'SELECT c.*, (SELECT COUNT(*) FROM dishes d WHERE d.category_id = c.id) AS n
       FROM categories c WHERE c.id = ?', [$editId])[0] ?? null) : null;
    if ($editId > 0 && !$editing) { flash('err', 'Cette catégorie n’existe plus.'); redirect('/admin/categories'); }
    $maxSort = 0;
    foreach ($categories as $c) $maxSort = max($maxSort, (int)$c['sort']);
    $this->view('admin/categories', compact('categories', 'editing', 'isNew', 'maxSort'), 'Catégories');
  }

  private function saveCategory(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $back = '/admin/categories'.($id > 0 ? '?edit='.$id : '?new=1');
    $name = $this->in('name', 80);
    if (mb_strlen($name) < 2) {
      keep_old($_POST);
      flash('err', 'Le nom de la catégorie doit contenir au moins 2 caractères.');
      redirect($back);
    }

    $base = slugify($this->in('slug', 80) !== '' ? $this->in('slug', 80) : $name);
    $slug = $base;
    $i = 2;
    while (Database::all('SELECT id FROM categories WHERE slug = ? AND id <> ?', [$slug, $id])) {
      $slug = $base.'-'.$i;
      $i++;
    }

    $sort = (int)($_POST['sort'] ?? 0);
    if ($sort <= 0) {
      $sort = 1 + (int)(Database::all('SELECT COALESCE(MAX(sort), 0) AS s FROM categories')[0]['s'] ?? 0);
    }

    if ($id > 0) {
      Database::run('UPDATE categories SET name = ?, slug = ?, sort = ? WHERE id = ?', [$name, $slug, $sort, $id]);
      flash('ok', 'Catégorie mise à jour.');
    } else {
      Database::run('INSERT INTO categories(name, slug, sort) VALUES(?,?,?)', [$name, $slug, $sort]);
      flash('ok', 'Catégorie « '.$name.' » créée.');
    }
    forget_old();
    redirect('/admin/categories');
  }

  private function deleteCategory(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $row = Database::all('SELECT name FROM categories WHERE id = ?', [$id])[0] ?? null;
    if (!$row) { flash('err', 'Catégorie introuvable.'); redirect('/admin/categories'); }

    $count = $this->scalar('SELECT COUNT(*) FROM dishes WHERE category_id = ?', [$id]);
    if ($count > 0 && empty($_POST['force'])) {
      flash('err', '« '.$row['name'].' » contient '.$count.' plat(s). Confirmez la suppression en cochant la case prévue à cet effet.');
      redirect('/admin/categories?edit='.$id);
    }
    if ($count > 0) {
      foreach (Database::all('SELECT image FROM dishes WHERE category_id = ?', [$id]) as $d) {
        remove_upload($d['image']);
      }
      Database::run('DELETE FROM dishes WHERE category_id = ?', [$id]);
    }
    Database::run('DELETE FROM categories WHERE id = ?', [$id]);
    flash('ok', 'Catégorie « '.$row['name'].' » supprimée.');
    redirect('/admin/categories');
  }

  private function moveCategory(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $dir = ($_POST['dir'] ?? 'up') === 'down' ? 'down' : 'up';
    $ids = array_map('intval', array_column(Database::all('SELECT id FROM categories ORDER BY sort, id'), 'id'));
    $pos = array_search($id, $ids, true);
    if ($pos === false) { flash('err', 'Catégorie introuvable.'); redirect('/admin/categories'); }
    $swap = $dir === 'up' ? $pos - 1 : $pos + 1;
    if ($swap >= 0 && $swap < count($ids)) {
      $tmp = $ids[$pos];
      $ids[$pos] = $ids[$swap];
      $ids[$swap] = $tmp;
      foreach ($ids as $index => $catId) {
        Database::run('UPDATE categories SET sort = ? WHERE id = ?', [$index + 1, $catId]);
      }
      flash('ok', 'Ordre des catégories mis à jour.');
    }
    redirect('/admin/categories');
  }

  /* ================================================================
   *  Plat du jour
   * ================================================================ */
  private function daily(): void {
    $supported = $this->hasTable('daily_special');
    $row = $supported ? (Database::all('SELECT * FROM daily_special WHERE id = 1')[0] ?? null) : null;
    $this->view('admin/daily', compact('row', 'supported'), 'Plat du jour');
  }

  private function saveDaily(): void {
    $this->guardPost();
    if (!$this->hasTable('daily_special')) {
      flash('err', 'Importez sql/migration_admin.sql pour activer le plat du jour.');
      redirect('/admin/plat-du-jour');
    }
    $name = $this->in('name', 120);
    $price = $this->price('price');
    $ingredients = $this->in('ingredients', 400);
    $errors = [];
    if (mb_strlen($name) < 2) $errors[] = 'Indiquez le nom du plat du jour.';
    if ($price === null) $errors[] = 'Indiquez un prix valide.';
    if ($ingredients === '') $errors[] = 'Décrivez la composition du plat.';
    if ($errors) { keep_old($_POST); flash('err', implode(' ', $errors)); redirect('/admin/plat-du-jour'); }

    $served = (string)($_POST['served_on'] ?? '');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $served)) $served = date('Y-m-d');

    $current = Database::all('SELECT image FROM daily_special WHERE id = 1')[0] ?? null;
    try { $upload = upload_image('image'); }
    catch (RuntimeException $ex) { keep_old($_POST); flash('err', $ex->getMessage()); redirect('/admin/plat-du-jour'); }

    $existing = clean_path($_POST['image_path'] ?? null);
    $image = $upload ?: (!empty($_POST['remove_image']) ? null : ($existing ?? ($current['image'] ?? null)));

    try {
      Database::run(
        'REPLACE INTO daily_special(id, name, description, ingredients, allergens, price, image, active, served_on) VALUES(1,?,?,?,?,?,?,?,?)',
        [$name, $this->in('description', 255), $ingredients, $this->in('allergens', 255), $price, $image,
         isset($_POST['active']) ? 1 : 0, $served]
      );
    } catch (Throwable $ex) {
      keep_old($_POST);
      flash('err', 'Enregistrement impossible : '.$ex->getMessage());
      redirect('/admin/plat-du-jour');
    }
    if ($upload && !empty($current['image']) && $current['image'] !== $image) remove_upload($current['image']);

    forget_old();
    flash('ok', isset($_POST['active']) ? 'Le plat du jour est en ligne.' : 'Le plat du jour a été enregistré (hors ligne).');
    redirect('/admin/plat-du-jour');
  }

  private function retireDaily(): void {
    $this->guardPost();
    if ($this->hasTable('daily_special')) {
      Database::run('UPDATE daily_special SET active = 0 WHERE id = 1');
    }
    flash('ok', 'Le plat du jour n’est plus affiché sur le site.');
    redirect('/admin/plat-du-jour');
  }

  /* ================================================================
   *  Informations, horaires, réseaux
   * ================================================================ */
  private function infos(): void {
    $s = $this->settings();
    $hours = Database::all('SELECT * FROM hours ORDER BY day');
    $socials = Database::all('SELECT * FROM socials ORDER BY id');
    $this->view('admin/infos', compact('s', 'hours', 'socials'), 'Informations');
  }

  private function saveSettings(): void {
    $this->guardPost();
    $value = [];
    foreach (self::SETTING_KEYS as $key) {
      $value[$key] = trim((string)($_POST[$key] ?? ''));
    }
    $errors = [];
    if (mb_strlen($value['name']) < 2) $errors[] = 'Le nom de la maison est requis.';
    if ($value['email'] !== '' && !filter_var($value['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Adresse e-mail invalide.';
    if ($value['whatsapp'] !== '') {
      $value['whatsapp'] = preg_replace('/[^0-9]/', '', $value['whatsapp']) ?? '';
      if (strlen($value['whatsapp']) < 6) $errors[] = 'Numéro WhatsApp invalide (chiffres uniquement).';
    }
    if ($value['maps_embed'] !== '' && !preg_match('#^https://[^\s"\'<>]+$#i', $value['maps_embed'])) {
      $errors[] = 'Le lien de la carte doit commencer par https://';
    }
    if ($value['lat'] !== '' && !is_numeric($value['lat'])) $errors[] = 'Latitude invalide.';
    if ($value['lng'] !== '' && !is_numeric($value['lng'])) $errors[] = 'Longitude invalide.';
    if ($errors) { keep_old($_POST); flash('err', implode(' ', $errors)); redirect('/admin/infos'); }

    try {
      foreach (self::SETTING_KEYS as $key) {
        Database::run('REPLACE INTO settings(k, v) VALUES(?, ?)', [$key, mb_substr($value[$key], 0, 2000)]);
      }
    } catch (Throwable $ex) {
      keep_old($_POST);
      flash('err', 'Enregistrement impossible : '.$ex->getMessage());
      redirect('/admin/infos');
    }
    forget_old();
    flash('ok', 'Les informations de la maison ont été enregistrées.');
    redirect('/admin/infos');
  }

  private function saveHours(): void {
    $this->guardPost();
    $rows = $_POST['hours'] ?? null;
    if (!is_array($rows)) { flash('err', 'Formulaire invalide.'); redirect('/admin/infos#horaires'); }
    foreach ($rows as $day => $row) {
      $day = (int)$day;
      if ($day < 1 || $day > 7 || !is_array($row)) continue;
      Database::run(
        'REPLACE INTO hours(day, open1, close1, open2, close2, closed) VALUES(?,?,?,?,?,?)',
        [$day,
         $this->timeValue($row['open1'] ?? ''),
         $this->timeValue($row['close1'] ?? ''),
         $this->timeValue($row['open2'] ?? ''),
         $this->timeValue($row['close2'] ?? ''),
         empty($row['closed']) ? 0 : 1]
      );
    }
    flash('ok', 'Les horaires ont été mis à jour.');
    redirect('/admin/infos#horaires');
  }

  private function saveSocial(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $network = strtolower(preg_replace('/[^A-Za-z0-9_]/', '', $this->in('network', 30)) ?: 'autre');
    $url = trim((string)($_POST['url'] ?? ''));
    if (!preg_match('#^https://[^\s"<>]+$#i', $url)) {
      flash('err', 'Indiquez une adresse complète, en https://');
      redirect('/admin/infos#reseaux');
    }
    if ($id > 0) {
      Database::run('UPDATE socials SET network = ?, url = ? WHERE id = ?', [$network, $url, $id]);
    } else {
      Database::run('INSERT INTO socials(network, url) VALUES(?,?)', [$network, $url]);
    }
    flash('ok', 'Réseau social enregistré.');
    redirect('/admin/infos#reseaux');
  }

  private function deleteSocial(): void {
    $this->guardPost();
    Database::run('DELETE FROM socials WHERE id = ?', [(int)($_POST['id'] ?? 0)]);
    flash('ok', 'Réseau social supprimé.');
    redirect('/admin/infos#reseaux');
  }

  /* ================================================================
   *  Galerie
   * ================================================================ */
  private function gallery(): void {
    $items = Database::all('SELECT * FROM gallery ORDER BY sort, id');
    $this->view('admin/galerie', compact('items'), 'Galerie');
  }

  private function saveGallery(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $alt = $this->in('alt', 160);
    $current = $id > 0 ? (Database::all('SELECT * FROM gallery WHERE id = ?', [$id])[0] ?? null) : null;
    if ($id > 0 && !$current) { flash('err', 'Image introuvable.'); redirect('/admin/galerie'); }

    try { $upload = upload_image('image'); }
    catch (RuntimeException $ex) { flash('err', $ex->getMessage()); redirect('/admin/galerie'); }

    $path = $upload ?: (clean_path($_POST['path'] ?? null) ?? ($current['path'] ?? null));
    if (!$path) { flash('err', 'Téléversez une image ou indiquez son chemin (/assets/img/…).'); redirect('/admin/galerie'); }

    $sort = (int)($_POST['sort'] ?? 0);
    if ($sort <= 0) {
      $sort = 1 + (int)(Database::all('SELECT COALESCE(MAX(sort), 0) AS s FROM gallery')[0]['s'] ?? 0);
    }

    if ($current) {
      Database::run('UPDATE gallery SET path = ?, alt = ?, sort = ? WHERE id = ?', [$path, $alt, $sort, $current['id']]);
      if ($current['path'] !== $path) remove_upload($current['path']);
      flash('ok', 'Image mise à jour.');
    } else {
      Database::run('INSERT INTO gallery(path, alt, sort) VALUES(?,?,?)', [$path, $alt, $sort]);
      flash('ok', 'Image ajoutée à la galerie.');
    }
    redirect('/admin/galerie');
  }

  private function deleteGallery(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $row = Database::all('SELECT path FROM gallery WHERE id = ?', [$id])[0] ?? null;
    if ($row) {
      Database::run('DELETE FROM gallery WHERE id = ?', [$id]);
      remove_upload($row['path']);
      flash('ok', 'Image retirée de la galerie.');
    }
    redirect('/admin/galerie');
  }

  /* ================================================================
   *  Demandes (réservations, messages, newsletter)
   * ================================================================ */
  private function requests(): void {
    $type = (string)($_GET['type'] ?? 'reservations');
    if (!in_array($type, ['reservations', 'messages', 'newsletter'], true)) $type = 'reservations';

    $counts = [
      'reservations' => $this->scalar('SELECT COUNT(*) FROM reservations'),
      'new'          => $this->scalar("SELECT COUNT(*) FROM reservations WHERE status = 'new'"),
      'messages'     => $this->scalar('SELECT COUNT(*) FROM contacts'),
      'newsletter'   => $this->scalar('SELECT COUNT(*) FROM newsletter'),
    ];

    $reservations = [];
    $messages = [];
    $newsletter = [];
    $status = (string)($_GET['status'] ?? '');

    if ($type === 'reservations') {
      if (in_array($status, ['new', 'confirmed', 'cancelled'], true)) {
        $reservations = Database::all('SELECT * FROM reservations WHERE status = ? ORDER BY id DESC LIMIT 300', [$status]);
      } else {
        $reservations = Database::all('SELECT * FROM reservations ORDER BY id DESC LIMIT 300');
      }
    } elseif ($type === 'messages') {
      $messages = Database::all('SELECT * FROM contacts ORDER BY id DESC LIMIT 300');
    } else {
      $newsletter = Database::all('SELECT * FROM newsletter ORDER BY id DESC LIMIT 500');
    }

    $this->view('admin/demandes', compact('type', 'status', 'counts', 'reservations', 'messages', 'newsletter'), 'Demandes');
  }

  private function reservationStatus(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $status = (string)($_POST['status'] ?? '');
    if (!in_array($status, ['new', 'confirmed', 'cancelled'], true)) {
      flash('err', 'Statut inconnu.');
      redirect('/admin/demandes');
    }
    Database::run('UPDATE reservations SET status = ? WHERE id = ?', [$status, $id]);
    flash('ok', 'Réservation mise à jour.');
    redirect('/admin/demandes?type=reservations');
  }

  private function deleteRequest(): void {
    $this->guardPost();
    $map = ['reservation' => 'reservations', 'message' => 'contacts', 'newsletter' => 'newsletter'];
    $type = (string)($_POST['type'] ?? '');
    $id = (int)($_POST['id'] ?? 0);
    if (!isset($map[$type])) { flash('err', 'Action inconnue.'); redirect('/admin/demandes'); }
    Database::run('DELETE FROM '.$map[$type].' WHERE id = ?', [$id]);
    flash('ok', 'Élément supprimé.');
    $back = $type === 'reservation' ? 'reservations' : ($type === 'message' ? 'messages' : 'newsletter');
    redirect('/admin/demandes?type='.$back);
  }

  private function exportNewsletter(): void {
    $rows = Database::all('SELECT email, created FROM newsletter ORDER BY id');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="newsletter-sultan-'.date('Y-m-d').'.csv"');
    $out = fopen('php://output', 'w');
    if ($out === false) exit;
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, ['email', 'inscription'], ';');
    foreach ($rows as $r) fputcsv($out, [$r['email'], $r['created']], ';');
    fclose($out);
    exit;
  }

  /* ================================================================
   *  Avis clients
   * ================================================================ */
  private function reviews(): void {
    $items = Database::all('SELECT * FROM reviews ORDER BY approved ASC, id DESC');
    $editId = (int)($_GET['edit'] ?? 0);
    $editing = $editId > 0 ? (Database::all('SELECT * FROM reviews WHERE id = ?', [$editId])[0] ?? null) : null;
    $isNew = isset($_GET['new']);
    $this->view('admin/avis', compact('items', 'editing', 'isNew'), 'Avis clients');
  }

  private function saveReview(): void {
    $this->guardPost();
    $id = (int)($_POST['id'] ?? 0);
    $author = $this->in('author', 80);
    $body = $this->in('body', 2000);
    $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
    $source = $this->in('source', 20) ?: 'google';
    $approved = isset($_POST['approved']) ? 1 : 0;
    if (mb_strlen($author) < 2 || mb_strlen($body) < 5) {
      keep_old($_POST);
      flash('err', 'L’auteur et le texte de l’avis sont nécessaires.');
      redirect('/admin/avis'.($id > 0 ? '?edit='.$id : '?new=1'));
    }
    if ($id > 0) {
      Database::run('UPDATE reviews SET author=?, rating=?, body=?, source=?, approved=? WHERE id=?',
        [$author, $rating, $body, $source, $approved, $id]);
      flash('ok', 'Avis mis à jour.');
    } else {
      Database::run('INSERT INTO reviews(author, rating, body, source, approved) VALUES(?,?,?,?,?)',
        [$author, $rating, $body, $source, $approved]);
      flash('ok', 'Avis ajouté.');
    }
    forget_old();
    redirect('/admin/avis');
  }

  private function deleteReview(): void {
    $this->guardPost();
    Database::run('DELETE FROM reviews WHERE id = ?', [(int)($_POST['id'] ?? 0)]);
    flash('ok', 'Avis supprimé.');
    redirect('/admin/avis');
  }

  /* ================================================================
   *  Compte administrateur
   * ================================================================ */
  private function account(): void {
    $this->view('admin/compte', [], 'Mon compte');
  }

  private function saveAccount(): void {
    $this->guardPost();
    $admin = $this->current();
    if (!$admin) redirect('/admin/login');
    $action = (string)($_POST['action'] ?? '');

    if ($action === 'email') {
      $email = mb_strtolower($this->in('email', 150));
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('err', 'Adresse e-mail invalide.');
        redirect('/admin/compte');
      }
      try {
        Database::run('UPDATE admins SET email = ? WHERE id = ?', [$email, $admin['id']]);
      } catch (Throwable $ex) {
        flash('err', 'Cette adresse est déjà utilisée par un autre compte.');
        redirect('/admin/compte');
      }
      flash('ok', 'Adresse e-mail mise à jour.');
      redirect('/admin/compte');
    }

    if ($action === 'password') {
      $current = (string)($_POST['current'] ?? '');
      $p1 = (string)($_POST['password'] ?? '');
      $p2 = (string)($_POST['password2'] ?? '');
      if (!password_verify($current, (string)$admin['password_hash'])) {
        flash('err', 'Le mot de passe actuel est incorrect.');
        redirect('/admin/compte');
      }
      if (strlen($p1) < 10) {
        flash('err', 'Le nouveau mot de passe doit contenir au moins 10 caractères.');
        redirect('/admin/compte');
      }
      if (!hash_equals($p1, $p2)) {
        flash('err', 'Les deux nouveaux mots de passe ne correspondent pas.');
        redirect('/admin/compte');
      }
      Database::run('UPDATE admins SET password_hash = ? WHERE id = ?',
        [password_hash($p1, PASSWORD_DEFAULT), $admin['id']]);
      session_regenerate_id(true);
      flash('ok', 'Mot de passe modifié.');
      redirect('/admin/compte');
    }

    redirect('/admin/compte');
  }

  /* ================================================================
   *  Outils internes
   * ================================================================ */
  private function view(string $tpl, array $data = [], string $title = 'Administration', string $shell = 'app'): void {
    $admin = $this->current();
    $page = $tpl;
    try {
      $brand = (string)(Database::all("SELECT v FROM settings WHERE k = 'name'")[0]['v'] ?? 'Sultan');
    } catch (Throwable $ex) {
      $brand = 'Sultan';
    }
    extract($data, EXTR_SKIP);
    ob_start();
    require __DIR__.'/../views/'.$tpl.'.php';
    $content = ob_get_clean();
    require __DIR__.'/../views/admin/layout.php';
  }

  private function dbIssue(Throwable $ex): void {
    http_response_code(500);
    $this->view('admin/db', ['message' => $ex->getMessage()], 'Base de données', 'guest');
  }

  private function guardPost(): void {
    if (strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET')) !== 'POST') redirect('/admin');
    if (!csrf_ok()) {
      flash('err', 'Session expirée, merci de réessayer.');
      redirect('/admin');
    }
    if (!empty($_POST['website'])) redirect('/admin');
  }

  private function settings(): array {
    $rows = Database::all('SELECT k, v FROM settings');
    return array_column($rows, 'v', 'k');
  }

  private function scalar(string $sql, array $params = []): int {
    $row = Database::all($sql, $params)[0] ?? [];
    $value = $row ? reset($row) : 0;
    return (int)($value ?: 0);
  }

  private function hasTable(string $table): bool {
    static $cache = [];
    if (array_key_exists($table, $cache)) return $cache[$table];
    try {
      Database::all('SELECT 1 FROM '.$table.' LIMIT 1');
      return $cache[$table] = true;
    } catch (Throwable $ex) {
      return $cache[$table] = false;
    }
  }

  private function in(string $key, int $max = 255): string {
    return mb_substr(trim((string)($_POST[$key] ?? '')), 0, $max);
  }

  private function price(string $key): ?float {
    $raw = str_replace([' ', "\u{00A0}", '€', ','], ['', '', '', '.'], (string)($_POST[$key] ?? ''));
    if ($raw === '' || !is_numeric($raw)) return null;
    $value = round((float)$raw, 2);
    return ($value > 0 && $value < 100000) ? $value : null;
  }

  private function timeValue($value): ?string {
    $value = trim((string)$value);
    if (!preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $value)) return null;
    return substr($value, 0, 5).':00';
  }
}
