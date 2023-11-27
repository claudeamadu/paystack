class Paystack {
    constructor(api_key) {
        this.api_key = api_key;
        this.END_POINT = 'https://api.paystack.co';
    }

    async setHttp(method, url, params) {
        const headers = {
            'Authorization': `Bearer ${this.api_key}`,
            'Cache-Control': 'no-cache'
        };

        const options = {
            method: method.toUpperCase(),
            headers: headers
        };

        if (method === 'POST' || method === 'PUT') {
            options.body = new URLSearchParams(params);
        }

        try {
            const response = await fetch(this.END_POINT + url, options);
            if (!response.ok) {
                throw new Error('Request Error: ' + response.statusText);
            }
            return await response.json();
        } catch (error) {
            // Handle request exception
            console.error('Error:', error);
            return null;
        }
    }

    // Bonus Start
    detectNetwork(phoneNumber) {
        const prefix = phoneNumber.substring(0, 3); // Extract the first three digits

        let network = "";
        switch (prefix) {
            case "053":
            case "055":
            case "059":
            case "024":
                network = "MTN";
                break;
            case "050":
            case "020":
                network = "VOD";
                break;
            case "057":
            case "027":
                network = "ATL";
                break;
            default:
                network = "";
                break;
        }

        return network;
    }
    // Bonus End

    async createSubAccount(name, bank, number, email) {
    }

    // Transfers Recipients Start
    async createTransferRecipient(name, bank, number, email) {
        const fields = {
            "type": "mobile_money",
            "name": name,
            "email": email,
            "account_number": number,
            "bank_code": bank,
            "currency": "GHS"
        };
        return await this.setHttp('POST', '/transferrecipient', fields);
    }

    async updateTransferRecipient(name, code, email) {
        const fields = {
            "type": "mobile_money",
            "name": name,
            "email": email
        };
        return await this.setHttp('PUT', `/transferrecipient/${code}`, fields);
    }

    async fetchTransferReceipient(code) {
        return await this.setHttp('GET', `/transferrecipient/${code}`, {});
    }

    async deleteTransferReceipient(code) {
        return await this.setHttp('DELETE', `/transferrecipient/${code}`, {});
    }
    async initiateTransfer(source, reason, amount, recipient) {
        const fields = {
            "source": source,
            "reason": reason,
            "amount": amount * 100,
            "recipient": recipient,
            "currency": "GHS"
        };
        return await this.setHttp('POST', '/transfer', fields);
    }

    async finalizeTransfer(transfer_code, otp) {
        const fields = {
            "transfer_code": transfer_code,
            "otp": otp
        };
        return await this.setHttp('POST', '/transfer/finalize_transfer', fields);
    }

    async fetchTransfer(transfer_code) {
        return await this.setHttp('GET', `/transfer/${transfer_code}`, {});
    }

    async verifyTransfer(reference) {
        const fields = {};
        return await this.setHttp('GET', `/transfer/verify/${reference}`, fields);
    }
    // Transfers Recipients End

    // Charge Start
    async charge(email, amount, network, phone_number) {
        const fields = {
            'email': email,
            'amount': amount * 100,
            'bank': {
                'code': network,
                'account_number': phone_number
            }
        };

        return await this.setHttp('POST', '/charge', fields);
    }

    async submitOTP(otp, reference) {
        const fields = {
            'otp': otp,
            'reference': reference
        };
        return await this.setHttp('POST', '/charge/submit_otp', fields);
    }

    async submitPIN(pin, reference) {
        const fields = {
            'pin': pin,
            'reference': reference
        };
        return await this.setHttp('POST', '/charge/submit_pin', fields);
    }

    async pendingCharge(pin, reference) {
        const fields = {};
        return await this.setHttp('GET', `/charge/${reference}`, fields);
    }
    // Charge End

    // Verification Start
    async verifyAccount(account_number, bank_code) {
        const params = {
            account_number: account_number,
            bank_code: bank_code
        };
        const queryParams = new URLSearchParams(params).toString();
        return await this.setHttp('GET', `/bank/resolve?${queryParams}`, {});
    }
    // Verification End
}
