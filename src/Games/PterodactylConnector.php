<?php
namespace src\Games;

class PterodactylConnector
{

    public function requestApi($method, $data, $url, $api)
    {
        $curl = curl_init();
        $curl_url = "https://game.powerful-hosting.fr/api/$api/" . $url;

        if($api == "client") {
            $api_key = 'ptlc_vumoAA8nAzNBLL3CoQxCzNO9h9aqRlLClN02gtxbezE';
        } else if($api == "application") {
            $api_key = 'ptla_nspsq74SuiTmXc8sGpTYNMUjRBTRMwYEJograzwlKjV';
        }
        curl_setopt($curl, CURLOPT_URL, $curl_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        $headers = [
            "Authorization: Bearer $api_key",
            "Accept: Application/vnd.pterodactyl.v1+json",
        ];
        if ($method === 'POST' || $method === 'PATCH' || $method === 'GET') {
            $jsonData = json_encode($data);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
            array_push($headers, "Content-Type: application/json");
            array_push($headers, "Content-Length: " . strlen($jsonData));
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        $responseData = json_decode($response, true);
        curl_close($curl);
        return $responseData;
    }

}