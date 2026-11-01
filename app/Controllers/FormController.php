<?php
final class FormController {
  private function guard(string $k): void {
    if (!csrf_ok()) json(['ok'=>false,'error'=>'Session expirée, rechargez la page.'],419);
    if (!empty($_POST['website'])) json(['ok'=>true]); // honeypot
    if (!throttle($k,5,600)) json(['ok'=>false,'error'=>'Trop de demandes, réessayez dans quelques minutes.'],429);
  }
  public function reserve(): void {
    $this->guard('res');
    $n = trim($_POST['name'] ?? ''); $ph = trim($_POST['phone'] ?? ''); $em = trim($_POST['email'] ?? '');
    $d = $_POST['date'] ?? ''; $t = $_POST['time'] ?? ''; $g = (int)($_POST['guests'] ?? 0); $note = mb_substr(trim($_POST['note'] ?? ''),0,255);
    $dt = DateTime::createFromFormat('Y-m-d H:i',"$d $t"); $err = [];
    if (mb_strlen($n)<2 || mb_strlen($n)>100) $err['name']='Indiquez votre nom.';
    if (!preg_match('/^[+0-9 ().-]{7,20}$/',$ph)) $err['phone']='Numéro de téléphone invalide.';
    if ($em && !filter_var($em,FILTER_VALIDATE_EMAIL)) $err['email']='E-mail invalide.';
    if (!$dt || $dt < new DateTime('+1 hour')) $err['date']='Choisissez un créneau à venir.';
    if ($g<1 || $g>20) $err['guests']='Entre 1 et 20 personnes. Au-delà, appelez-nous.';
    if ($err) json(['ok'=>false,'errors'=>$err],422);
    Database::run('INSERT INTO reservations(name,phone,email,rdate,rtime,guests,note) VALUES(?,?,?,?,?,?,?)',[$n,$ph,$em,$d,$t,$g,$note]);
    json(['ok'=>true,'message'=>'Demande reçue. Nous vous confirmons par téléphone.']);
  }
  public function contact(): void {
    $this->guard('ct');
    $n=trim($_POST['name']??''); $em=trim($_POST['email']??''); $m=trim($_POST['message']??'');
    if (mb_strlen($n)<2 || !filter_var($em,FILTER_VALIDATE_EMAIL) || mb_strlen($m)<5 || mb_strlen($m)>2000) json(['ok'=>false,'error'=>'Vérifiez les champs du formulaire.'],422);
    Database::run('INSERT INTO contacts(name,email,message) VALUES(?,?,?)',[$n,$em,$m]);
    json(['ok'=>true,'message'=>'Message envoyé. Merci.']);
  }
  public function newsletter(): void {
    $this->guard('nl'); $em=trim($_POST['email']??'');
    if (!filter_var($em,FILTER_VALIDATE_EMAIL)) json(['ok'=>false,'error'=>'E-mail invalide.'],422);
    Database::run('INSERT IGNORE INTO newsletter(email) VALUES(?)',[$em]);
    json(['ok'=>true,'message'=>'Inscription confirmée.']);
  }
}
