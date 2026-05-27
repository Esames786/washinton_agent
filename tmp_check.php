<?php
$pdo = new PDO('mysql:host=199.250.220.27;port=3306;dbname=hellotransport_databases', 'hellotransport_databases', 'hellotransport_databases');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->query("SELECT * FROM email_accounts WHERE id = 5");
$acc = $stmt->fetch(PDO::FETCH_ASSOC);

// Show everything except encrypted password
$safe = $acc;
$safe['password_enc'] = substr($acc['password_enc'], 0, 20) . '...(truncated)';
echo "Full email_account row:\n" . json_encode($safe, JSON_PRETTY_PRINT) . "\n";
