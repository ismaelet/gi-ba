<?php

//set variables
$sent = false;
$error = false;

$services = [
	[
		'title' => 'Abbigliamento professionale per',
		'desc' => '
			Alberghi<br>
			Ristoranti<br>
			Centri estetici<br>
			Studi medici',
	],
	[
		'title' => 'Personalizzazione di',
		'desc' => '
			T-shirt ~ Felpe<br>
			Cappellini<br>
			Forniture sportive<br>
			Capi in genere<br>
			Decoro automezzi',
	],
	[
		'title' => 'Creazione di',
		'desc' => '
			Brochures ~ Striscioni<br>
			Biglietti da visita<br>
			Adesivi pre-spaziati ~ Ricami<br>
			Stampa a caldo<br>
			Stampa digitale e
			serigrafica',
	]
];

bump($services);


$images = [
	false,
	['T-shirt &<br>cappellini', '#271D28'],
	false,
	['Felpe &<br>hoodie', '#EEAD47'],
	['Abbigliamento<br>professionale', '#EED09C'],
	false,
	['Brochures &<br>striscioni', '#69636A'],
	false,
	['Abbigliamento<br>professionale', '#EED09C'],
];

bump($images);

//loading
if (!isset($_SESSION['loaded'])) $_SESSION['loaded'] = false;
else $_SESSION['loaded'] = true;

//send mail
if (isset($_POST['submit']) && !$sent) {
	
	$name = trim($_POST['name']);
	$mail = trim($_POST['mail']);
	$msg = trim($_POST['text']);
	
	$serv = explode('@', $mail)[1];
	$serv = explode('.', $serv)[0];
	
	if (!in_array($serv, ['tiscali', 'gmail', 'libero', 'yahoo', 'hotmail', 'icloud', 'outlook'])) $error = 'mail_bad';
	else if (preg_match("/(sexy|bitcoin|wellness|dollars|https|free|business)/i", $msg)) $error = 'word_bad';
	else {
		$to      = sysData('cont_mail');
		$subject = 'Richiesta informazioni';

		$headers =
			'From: GI&BA Site <no-reply@shippingdog.com>' . "\r\n" .
			'Reply-To: '.$mail."\r\n" .
			'X-Mailer: PHP/' . phpversion();
		$headers .= 'MIME-Version: 1.0' . "\r\n";  
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";  

		$message = 'Messaggio da '.$name.' ('.$mail.'):<br/><br/>'.$msg;
		
		mail($to, $subject, $message, $headers);

		$sent = true;
	}
}

//store variables
$this->set('services', $services);
$this->set('images', $images);
$this->set('sent', $sent);
$this->set('error', $error);