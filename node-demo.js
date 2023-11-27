const Paystack = require('./Paystack-node'); // Import the Paystack class from the file

const paystack = new Paystack('sk_test_4e0e54435f8873d711f92c05159e4e149c197344');

paystack.verifyAccount('0558382705', 'MTN')
    .then(response => {
        console.log(response);
    })
    .catch(error => {
        console.error(error);
    });