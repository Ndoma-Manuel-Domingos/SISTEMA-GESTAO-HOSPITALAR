<?php

namespace App\Traits;

use App\Models\Entidade;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait UsesAgtConfig
{
    protected string $privateSoftwareKey;
    protected string $privateCustomerKey;
    protected string $taxRegistrationNumber;
    protected string $productId;
    protected string $productVersion;
    protected string $softwareValidationNumber;
    protected string $urlBase;
    protected string $schemaVersion;
    
    protected function loadAgtConfig(): void
    {
        $this->privateSoftwareKey = config('agt.private_software_key');
        $this->privateCustomerKey = config('agt.private_customer_key');
        $this->productVersion = config('agt.product_version');
        $this->taxRegistrationNumber = config('agt.tax_registration_number');
        $this->productId = config('agt.product_id');
        $this->schemaVersion = config('agt.schema_version');
        $this->urlBase = config('agt.url_base');
        $this->softwareValidationNumber = config('agt.software_validation_number');
    }
    
    public function urlBase()
    {
        return $this->urlBase;
    }
    
    public function schemaVersion()
    {
        return $this->schemaVersion;
    }
    
    public function softwareInfoDetail()
    {
        return $this->utf8ize([
            "productId" => $this->productId,
            "productVersion" => $this->productVersion,
            "softwareValidationNumber" => $this->softwareValidationNumber
        ]);
    }
    
    public function jwsSoftwareSignature()
    {
        return JWT::encode(
            $this->softwareInfoDetail(),
            $this->privateSoftwareKey,
            'RS256'
        );
    }
    
    private function utf8ize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($data)) {
            if (!mb_detect_encoding($data, 'UTF-8', true)) {
                $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
            }
        }

        return $data;
    }
    
    public function submitFiscalDocument(array $data, $URL, $submissionUUID = "")
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post(
            $this->urlBase() . $URL,
            [
                'auth' => [env('FE_USER_HML'), env('FE_PASS_HML')],
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json' => $data,
            ]
        );

        return response()->json([
            'success' => true,
            'submissionUUID' => $submissionUUID,
            'response' => json_decode($response->getBody(), true),
        ]);
    }
        
    public function errorSubmitFiscalDocument($e)
    {
        return response()->json([
            'success' => false,
            'status' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
            'message' => $e->hasResponse()
                ? json_decode($e->getResponse()->getBody(), true)
                : $e->getMessage(),
        ], 500);
    }
    
    public function round(float $valor, int $casas = 2): float
    {

        return ceil($valor * 100) / 100;
        $fator = 10 ** $casas;
        return ceil($valor * $fator) / $fator;
    }
    
    public function geSequenciaAtingiuLimite(string $doc, int $numSequenciaFactura)
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return DB::table('fe_series')
            ->where('seriesYear', $yearNow)
            ->where('documentType', $doc)
            ->whereRaw('CAST(lastDocumentNo AS UNSIGNED) < ?', [$numSequenciaFactura])
            ->first();
    }
    
    public function getNumeroSerieDocumento($doc)
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return DB::table('fe_series')
            ->where('seriesYear', $yearNow)
            ->where('documentType', $doc)
            ->first();
    }
    
}