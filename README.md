# paystack
Simplified Paystack Classes for your projects
### PHP & Laravel
```php
//PHP
require_once('Paystack.php');
$paystack = new Paystack('sk_test_test');
//Laravel
use Paystack;
$paystack = new Paystack('sk_test_test');

//Transfers
createTransferReceipient($name, $bank, $number, $email)
updateTransferReceipient($name, $code, $email)
fetchTransferReceipient($code)
deleteTransferReceipient($code)
initiateTransfer($source, $reason, $amount, $recipient)
finalizeTransfer($transfer_code, $otp)
fetchTransfer($transfer_code)
verifyTransfer($reference)

//Charge
charge($email, $amount, $network, $phone_number)
submitOTP($otp, $reference)
submitPIN($pin, $reference)
pendingCharge($reference)

//Verification
verifyAccount($account_number, $bank_code)
```

### JavaScript & Nodejs
```js
//Nodejs
const Paystack = require('./Paystack-node'); // Import the Paystack class from the file
const paystack = new Paystack('sk_test_test');
//JavaScript
const paystack = new Paystack('sk_test_test');

//Example
paystack.verifyAccount('0558382705', 'MTN')
.then(response => {
    console.log(response);
})
.catch(error => {
    console.error(error);
});
```
