<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paystack extends Model
{
    private $api_key;

    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }
    private function setHttp($method, $url, $params)
    {
        $END_POINT = 'https://api.paystack.co';
        try {
            $headers = [
                'Authorization: Bearer ' . $this->api_key,
                "Cache-Control: no-cache",
            ];

            $curl = curl_init();

            // Set common cURL options
            curl_setopt_array($curl, [
                CURLOPT_URL => $END_POINT . $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => strtoupper($method),
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => http_build_query($params),
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                throw new Exception('Request Error: ' . $error);
            }
			// Decode the JSON response
			$jsonResponse = json_decode($response);

			if (json_last_error() !== JSON_ERROR_NONE) {
				throw new Exception('Invalid JSON response');
			}

			return $jsonResponse;
        } catch (Exception $exception) {
            // Handle request exception
            return null;
        }
    }

    //Bonus Start
    public function detectNetwork($phoneNumber)
    {
        $prefix = substr($phoneNumber, 0, 3); // Extract the first three digits

        switch ($prefix) {
            case "053":
            case "055":
            case "059":
            case "024":
                $network = "MTN";
                break;
            case "050":
            case "020":
                $network = "VOD";
                break;
            case "057":
            case "027":
                $network = "ATL";
                break;
            default:
                $network = "";
                break;
        }

        return $network;
    }
    //Bonus End

    public function createSubAccount($name, $bank, $number, $email)
    {
    }

    // Transfers Recipients Start
    public function createTransferReceipient($name, $bank, $number, $email)
    {
        $fields = [
            "type" => "mobile_money",
            "name" => $name,
            "email" => $email,
            "account_number" => $number,
            "bank_code" => $bank,
            "currency" => "GHS"
        ];
        return $this->setHttp('post', '/transferrecipient', $fields);
    }
    public function updateTransferReceipient($name, $code, $email)
    {
        $fields = [
            "type" => "mobile_money",
            "name" => $name,
            "email" => $email
        ];
        return $this->setHttp('put', '/transferrecipient/' . $code, $fields);
    }
    public function fetchTransferReceipient($code)
    {
        $fields = [];
        return $this->setHttp('get', '/transferrecipient/' . $code, $fields);
    }
    public function deleteTransferReceipient($code)
    {
        $fields = [];
        return $this->setHttp('delete', '/transferrecipient/' . $code, $fields);
    }
    // Transfers Recipients End

    //Transfers Start
    public function initiateTransfer($source, $reason, $amount, $recipient)
    {
        $fields = [
            "source" => $source,
            "reason" => $reason,
            "amount" => $amount * 100,
            "recipient" => $recipient,
            "currency" => "GHS"
        ];
        return $this->setHttp('post', '/transfer', $fields);
    }
    public function finalizeTransfer($transfer_code, $otp)
    {
        $fields = [
            "transfer_code" => $transfer_code,
            "otp" => $otp,
        ];
        return $this->setHttp('post', '/transfer/finalize_transfer', $fields);
    }
    public function fetchTransfer($transfer_code)
    {
        $fields = [];
        return $this->setHttp('get', '/transfer/'.$transfer_code, $fields);
    }
    public function verifyTransfer($reference)
    {
        $fields = [];
        return $this->setHttp('get', '/transfer/verify/'.$reference, $fields);
    }
    //Transfers End

    //Charge Start
        public function charge($email, $amount, $network, $phone_number)
        {
            $fields = [
                'email' => $email,
                'amount' => $amount*100,
                'currency' => "GHS",
                "mobile_money" => [
                    "provider" => $network,
                    "phone" => $phone_number
                ]
            ];
            return $this->setHttp('post', '/charge', $fields);
        }
    public function submitOTP($otp, $reference)
    {
        $fields = [
            'otp' => $otp,
            'reference' => $reference
        ];
        return $this->setHttp('post', '/charge/submit_otp', $fields);
    }
    public function submitPIN($pin, $reference)
    {
        $fields = [
            'pin' => $pin,
            'reference' => $reference
        ];
        return $this->setHttp('post', '/charge/submit_pin', $fields);
    }
    public function pendingCharge($pin, $reference)
    {
        $fields = [];
        return $this->setHttp('get', '/charge/'.$reference, $fields);
    }
    //Charge Start

    //Verification Start
    public function verifyAccount($account_number, $bank_code)
    {
        $fields = [];
        return $this->setHttp('get', "/bank/resolve?account_number=$account_number&bank_code=$bank_code", $fields);
    }
    //Verification End
}

?>
