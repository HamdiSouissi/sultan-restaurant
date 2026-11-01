<?php
final class FormController {
  private function guard(string $k): void {
    if (!csrf_ok()) json(['ok'=>false,'error'=>'Sessione scaduta, ricaricate la pagina.'],419);
    if (!empty($_POST['website'])) json(['ok'=>true]); // honeypot
    if (!throttle($k,5,600)) json(['ok'=>false,'error'=>'Troppe richieste, riprovate tra qualche minuto.'],429);
  }
  public function reserve(): void {
    $this->guard('res');
    $n = trim($_POST['name'] ?? ''); $ph = trim($_POST['phone'] ?? ''); $em = trim($_POST['email'] ?? '');
    $d = $_POST['date'] ?? ''; $t = $_POST['time'] ?? ''; $g = (int)($_POST['guests'] ?? 0); $note = mb_substr(trim($_POST['note'] ?? ''),0,255);
    $dt = DateTime::createFromFormat('Y-m-d H:i',"$d $t"); $err = [];
    if (mb_strlen($n)<2 || mb_strlen($n)>100) $err['name']='Indicate il vostro nome.';
    if (!preg_match('/^[+0-9 ().-]{7,20}$/',$ph)) $err['phone']='Numero di telefono non valido.';
    if ($em && !filter_var($em,FILTER_VALIDATE_EMAIL)) $err['email']='Indirizzo e-mail non valido.';
    if (!$dt || $dt < new DateTime('+1 hour')) $err['date']='Scegliete un orario futuro.';
    if ($g<1 || $g>20) $err['guests']='Da 1 a 20 persone. Oltre, chiamateci.';
    if ($err) json(['ok'=>false,'errors'=>$err],422);
    Database::run('INSERT INTO reservations(name,phone,email,rdate,rtime,guests,note) VALUES(?,?,?,?,?,?,?)',[$n,$ph,$em,$d,$t,$g,$note]);
    json(['ok'=>true,'message'=>'Richiesta ricevuta. Vi confermiamo per telefono.']);
  }
  public function contact(): void {
    $this->guard('ct');
    $n=trim($_POST['name']??''); $em=trim($_POST['email']??''); $m=trim($_POST['message']??'');
    if (mb_strlen($n)<2 || !filter_var($em,FILTER_VALIDATE_EMAIL) || mb_strlen($m)<5 || mb_strlen($m)>2000) json(['ok'=>false,'error'=>'Controllate i campi del modulo.'],422);
    Database::run('INSERT INTO contacts(name,email,message) VALUES(?,?,?)',[$n,$em,$m]);
    json(['ok'=>true,'message'=>'Messaggio inviato. Grazie.']);
  }
  public function newsletter(): void {
    $this->guard('nl'); $em=trim($_POST['email']??'');
    if (!filter_var($em,FILTER_VALIDATE_EMAIL)) json(['ok'=>false,'error'=>'Indirizzo e-mail non valido.'],422);
    Database::run('INSERT IGNORE INTO newsletter(email) VALUES(?)',[$em]);
    json(['ok'=>true,'message'=>'Iscrizione confermata.']);
  }
}
