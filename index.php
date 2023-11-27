<?php
require_once('Paystack.php');

$paystack = new Paystack('sk_test_4e0e54435f8873d711f92c05159e4e149c197344');
//$response = $paystack->createTransferReceipient('Claude Amadu', 'MTN', '0558382705', 'obiriclaude@gmail.com');
//$response = $paystack->verifyAccount("0558382705","MTN");
$response = $paystack->detectNetwork("0558382705");
echo $response;
