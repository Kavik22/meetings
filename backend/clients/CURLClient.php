<?php

namespace app\clients;

use Yii;
use yii\helpers\Url;

class CURLClient
{
    public function doRequest($url, $data, $method = 'POST'): array
    {
        $ch = curl_init($url);
        try {
            if ($method == 'POST') {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                $errorMsg = curl_error($ch);
                Yii::error("CURL error: $errorMsg", __METHOD__);
                return [
                    'success' => false,
                    'error' => $errorMsg,
                    'code' => curl_errno($ch),
                ];
            }
    
            $decodedResponse = json_decode($response, true);
    
            return [
                'success' => true,
                'data' => $decodedResponse,
            ];
        } catch (\Throwable $e) {
            Yii::error("Exception: " . $e->getMessage(), __METHOD__);
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        } finally {
            curl_close($ch);
        }
    }
}