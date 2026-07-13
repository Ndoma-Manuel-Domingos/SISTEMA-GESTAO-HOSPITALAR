<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class FacturaElectronicaController extends Controller
{

    private string $documento;
    private string $factura;
    private string $requestID;

    private $queryStartDate;
    private $queryEndDate;
    private string $seriesYear;
    private string $seriesCode;
    private string $jwsSoftwareSignature;

    private array $payloadHeader;

    public function __construct()
    {
        $this->documento = "5001183745";
        $this->factura = "FT EA-V2025/5";
        $this->requestID = "202600000008812";

        // FT9125S221N/10
        
        $this->queryStartDate = "2026-01-06";
        $this->queryEndDate = "2026-01-07";
        
        $this->seriesYear = "2026";
        $this->seriesCode = "RG-2026";

        $this->payloadHeader = [
            "productId" => "Meu ERP CERTO",
            "productVersion" => "1.0.1",
            "softwareValidationNumber" => "C_134"
        ];
    }
    
    public function ConsultarNif(Request $request)
    {

        // $softwareInfo = [
        //     "productId" => "ANGOSIS-LD",
        //     "productVersion" => "1.0",
        //     "softwareValidationNumber" => "0000/AGT/2026"
        // ];

        // $privateKey = file_get_contents(storage_path('app/public/keys/private.pem'));

        // dd(storage_path('app/public/keys/private.pem'));

        $payload = [
            "productId" => "ANGOSIS-LD",
            "productVersion" => "1.0.1",
            "softwareValidationNumber" => "C_250"
        ];
        
        $token = $this->gerarJwtRs256(
            $payload,
            storage_path('app/public/keys/private.pem')
        );

        dd($token);

    
        // Validação dos parâmetros
        $request->validate([
            'tipoDocumento' => 'required|string',
            'numeroDocumento' => 'required|string',
        ]);

        try {
            $response = Http::get(
                'https://portaldocontribuinte.minfin.gov.ao/consultar-fe',
                [
                    'tipoDocumento' => $request->tipoDocumento,
                    'numeroDocumento' => $request->numeroDocumento,
                ]
            );

            if ($response->failed()) {
                return response()->json([
                    'sucesso' => false,
                    'mensagem' => 'Erro ao consultar o serviço externo'
                ], 502);
            }

            return response()->json([
                'sucesso' => true,
                'dados' => $response->json()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso' => false,
                'erro' => $e->getMessage()
            ], 500);
        }

    }

    public function payload()
    {

        $payload = [
            'taxRegistrationNumber' => '500123456789',
            'seriesCode' => 'FT-2025-EST001',
            'seriesYear' => 2025,
            'documentType' => 'FT',
            'firstDocumentNumber' => 1
        ];

        $privateKey = file_get_contents(storage_path('keys/private.pem'));

        openssl_sign(
            json_encode($payload, JSON_UNESCAPED_SLASHES),
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );

        $jwsSignature = base64_encode($signature);

        dd($jwsSignature);


    }
    
    public function solicitarSerie(Request $request)
    {
        $privateKeyPath = storage_path('app/public/keys/private.pem');
        
        if (!file_exists($privateKeyPath)) {
            dd("Arquivo não encontrado: " . $privateKeyPath);
        }

        $privateKey = file_get_contents($privateKeyPath);

        $invoice = [
            "schemaVersion" => "1.0",
            "submissionUUID" => Str::uuid()->toString(),
            "taxRegistrationNumber" => $this->documento,
            "submissionTimeStamp" => Carbon::now('UTC')->format('Y-m-d\TH:i:s\Z'),
            'softwareInfo' => [
                'softwareInfoDetail' => $this->payloadHeader,
                'jwsSoftwareSignature' => 'eyJ0eXAiOiJKT1NFIiwiYWxnIjoiUlMyNTYifQ.eyJwcm9kdWN0SWQiOiJNZXUgRVJQIENFUlRPIiwicHJvZHVjdFZlcnNpb24iOiIxLjAuMSIsInNvZnR3YXJlVmFsaWRhdGlvbk51bWJlciI6IkNfMTM0In0.VE3zkvOJOpqBfz4wpx4KCgcwOGgzGUP3MSMbaHCDnHhwOaeA6jlccBW9HjgQvg2tYCVVq0imrU_z0grEHNthhG4xD3afSOD1_RzvHs8Tc45dvztHJzB4gF0CAX-yIDwi7XcHiMRY0vkXOETBeHKewg0ktWSnZ7SLf4GxGzE7ry2u_pmhqhCPhxpa0oGQ_rBJUYkEAFg1OwaqjwvzCFgdT11r-XsHmnkcfJX_ktj59RWR_zgbytiCRtwCK9LNUflveS5GzUaCXbPn2deQ3F2hPldLECEa_ahwoapoK1LhkgOAVyPLJf6M1Cm09Le7rkdSaWQSW5BI_sPx5YaUaXkeqg',
            ],
            "seriesYear" => $this->seriesYear,
            "documentType" => "FT",
            "establishmentNumber" => "SEDE",
            "jwsSignature" => "string",
            "seriesContingencyIndicator" => "N",
        ];

        try {
            $client = new \GuzzleHttp\Client();
        
            $response = $client->post(
                'https://sifphml.minfin.gov.ao/sigt/fe/v1/solicitarSerie',
                [
                    'auth' => ['ws.hml.asis', 'mfn20162025'],
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'json' => $invoice,
                ]
            );
        
            return response()->json([
                'success' => true,
                'response' => json_decode($response->getBody(), true),
            ]);
        
        } catch (RequestException $e) {
            return response()->json([
                'success' => false,
                'status' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                'error' => $e->hasResponse() ? json_decode($e->getResponse()->getBody(), true) : $e->getMessage(),
            ], 500);
        }
        
    }
    
    public function listarSeries(Request $request)
    {
        
        $privateKeyPath = storage_path('app/public/keys/private.pem');
        
        if (!file_exists($privateKeyPath)) {
            dd("Arquivo não encontrado: " . $privateKeyPath);
        }

        $privateKey = file_get_contents($privateKeyPath);

        $payload = [
            "productId" => "Meu ERP CERTO",
            "productVersion" => "1.0.1",
            "softwareValidationNumber" => "C_134"
        ];

        $jwsSoftwareSignature = JWT::encode($payload, $privateKey, 'RS256');
        
        $invoice = [
            "schemaVersion" => "1.0",
            "taxRegistrationNumber" => $this->documento,
            "submissionTimeStamp" => Carbon::now()->format('Y-m-d\TH:i:s'),

            "seriesCode" => "FT4526S954N",
            "seriesYear" => $this->seriesYear,
            "seriesStatus" => "A",
            "documentType" => "FT",
            "establishmentNumber" => "SEDE",
            "jwsSignature" => "string",
            'softwareInfo' => [
                'softwareInfoDetail' => $this->payloadHeader,
                'jwsSoftwareSignature' => 'eyJ0eXAiOiJKT1NFIiwiYWxnIjoiUlMyNTYifQ.eyJwcm9kdWN0SWQiOiJNZXUgRVJQIENFUlRPIiwicHJvZHVjdFZlcnNpb24iOiIxLjAuMSIsInNvZnR3YXJlVmFsaWRhdGlvbk51bWJlciI6IkNfMTM0In0.VE3zkvOJOpqBfz4wpx4KCgcwOGgzGUP3MSMbaHCDnHhwOaeA6jlccBW9HjgQvg2tYCVVq0imrU_z0grEHNthhG4xD3afSOD1_RzvHs8Tc45dvztHJzB4gF0CAX-yIDwi7XcHiMRY0vkXOETBeHKewg0ktWSnZ7SLf4GxGzE7ry2u_pmhqhCPhxpa0oGQ_rBJUYkEAFg1OwaqjwvzCFgdT11r-XsHmnkcfJX_ktj59RWR_zgbytiCRtwCK9LNUflveS5GzUaCXbPn2deQ3F2hPldLECEa_ahwoapoK1LhkgOAVyPLJf6M1Cm09Le7rkdSaWQSW5BI_sPx5YaUaXkeqg',
            ]
        ];
        
        try {
            $client = new \GuzzleHttp\Client();
        
            $response = $client->post(
                'https://sifphml.minfin.gov.ao/sigt/fe/v1/listarSeries',
                [
                    'auth' => ['ws.hml.asis', 'mfn20162025'],
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'json' => $invoice,
                ]
            );
        
            return response()->json([
                'success' => true,
                'response' => json_decode($response->getBody(), true),
            ]);
        
        } catch (RequestException $e) {
            return response()->json([
                'success' => false,
                'status' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                'error' => $e->hasResponse() ? json_decode($e->getResponse()->getBody(), true) : $e->getMessage(),
            ], 500);
        }
        
    }
        
    public function registrarFactura(Request $request)
    {
                
        $privateKeyPath = storage_path('app/public/keys/private.pem');
        
        if (!file_exists($privateKeyPath)) {
            dd("Arquivo não encontrado: " . $privateKeyPath);
        }

        $privateKey = file_get_contents($privateKeyPath);
        
        $nowUtc = Carbon::now('UTC');
      
        $documentDate = $nowUtc->format('Y-m-d');
        
        $systemEntryDate = $nowUtc->format('Y-m-d\TH:i:s\Z');

        $factura = Venda::with(['items.produto.taxa_imposto'])->latest()->first();

        $lines = [];
            
        foreach($factura->items as $key => $value) {
            $lines[] = [
                'lineNumber' => $key + 1,
                'productCode' => $value->produto->id,
                'productDescription' => $value->produto->nome,
                'quantity' => $value->quantidade,
                'unitOfMeasure' => 'UN',
                'unitPrice' => $value->valor_base,
                'unitPriceBase' => $value->valor_base,
                'debitAmount' => 0,
                'creditAmount' => $value->valor_base * $value->quantidade,
                'taxes' => [
                    [
                        'taxType' => 'IVA',
                        // 'taxType' => $value->produto->taxa_imposto->tax_type,
                        'taxCountryRegion' => 'AO',
                        'taxCode' => $value->produto->taxa_imposto->codigo,
                        'taxPercentage' => $value->produto->taxa_imposto->valor,
                        'taxContribution' => $value->valor_iva,
                    ],
                ],
                'settlementAmount' => 0
            ];
        }

        $payloadJwsDocumentSignature = [
            "documentNo"  => $factura->factura_next,
            "taxRegistrationNumber"  => $this->documento,
            "documentType"  => $factura->factura,
            "documentDate"  => $documentDate,
            "customerTaxID"  => $factura->documento_nif,
            "customerCountry"  => "AO",
            "companyName"  => $factura->nome_cliente,
            "documentTotals"  => [
                "taxPayable"  => $factura->total_iva,
                "netTotal"  => $factura->total_incidencia,
                "grossTotal"  => $factura->total_incidencia + $factura->total_iva,
            ]
        ];
                
        $jwsDocumentSignature = JWT::encode($payloadJwsDocumentSignature, $privateKey, 'RS256');

        // $payload = [
        //     "productId" => "ANGOSIS-LD",
        //     "productVersion" => "1.0",
        //     "softwareValidationNumber" => "FE/117/AGT/2026"
        // ];
        
        // $jwsSoftwareSignature = $this->gerarJwtRs256( $payload, storage_path('app/public/keys/private.pem'));

        $invoice = [
            'schemaVersion' => '1.0',
            'submissionUUID' => Str::uuid()->toString(),
            'taxRegistrationNumber' => $this->documento,
            'submissionTimeStamp' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'softwareInfo' => [
                'softwareInfoDetail' => $this->payloadHeader,
                'jwsSoftwareSignature' => 'eyJ0eXAiOiJKT1NFIiwiYWxnIjoiUlMyNTYifQ.eyJwcm9kdWN0SWQiOiJNZXUgRVJQIENFUlRPIiwicHJvZHVjdFZlcnNpb24iOiIxLjAuMSIsInNvZnR3YXJlVmFsaWRhdGlvbk51bWJlciI6IkNfMTM0In0.VE3zkvOJOpqBfz4wpx4KCgcwOGgzGUP3MSMbaHCDnHhwOaeA6jlccBW9HjgQvg2tYCVVq0imrU_z0grEHNthhG4xD3afSOD1_RzvHs8Tc45dvztHJzB4gF0CAX-yIDwi7XcHiMRY0vkXOETBeHKewg0ktWSnZ7SLf4GxGzE7ry2u_pmhqhCPhxpa0oGQ_rBJUYkEAFg1OwaqjwvzCFgdT11r-XsHmnkcfJX_ktj59RWR_zgbytiCRtwCK9LNUflveS5GzUaCXbPn2deQ3F2hPldLECEa_ahwoapoK1LhkgOAVyPLJf6M1Cm09Le7rkdSaWQSW5BI_sPx5YaUaXkeqg',
            ],
            
            'numberOfEntries' => 1,
            'documents' => [
                [
                    'documentNo' => $factura->factura_next,
                    'documentStatus' => 'N',
                    'jwsDocumentSignature' => $jwsDocumentSignature,
                    'documentDate' => $documentDate,
                    'documentType' => $factura->factura,
                    'systemEntryDate' => $systemEntryDate,
                    "customerTaxID"  => $factura->documento_nif,
                    "customerCountry"  => "AO",
                    "companyName"  => $factura->nome_cliente,

                    'lines' => $lines,

                    'documentTotals' => [
                        "taxPayable"  => $factura->total_iva,
                        "netTotal"  => $factura->total_incidencia,
                        "grossTotal"  => $factura->total_incidencia + $factura->total_iva,
                    ],
        
                    'withholdingTaxList' => [
                        [
                            'withholdingTaxType' => 'IRT',
                            'withholdingTaxDescription' => 'Retenção na fonte',
                            'withholdingTaxAmount' => 16.5,
                        ],
                    ],
                ],
            ],
        ];
        

        dd($invoice);

        try {
            $client = new \GuzzleHttp\Client();
        
            $response = $client->post(
                'https://sifphml.minfin.gov.ao/sigt/fe/v1/registarFactura',
                [
                    'auth' => ['ws.hml.asis', 'mfn20162025'],
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'json' => $invoice,
                ]
            );
        
            return response()->json([
                'success' => true,
                'response' => json_decode($response->getBody(), true),
            ]);
        
        } catch (RequestException $e) {
        
            return response()->json([
                'success' => false,
                'status' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                'error' => $e->hasResponse()
                    ? json_decode($e->getResponse()->getBody(), true)
                    : $e->getMessage(),
            ], 500);
        }
        
    }
    
    public function obterEstado(Request $reques)
    {
    
        $privateKeyPath = storage_path('app/public/keys/private.pem');
        
        if (!file_exists($privateKeyPath)) {
            dd("Arquivo não encontrado: " . $privateKeyPath);
        }

        $privateKey = file_get_contents($privateKeyPath);
        
        $payloadJwsSignature = [
            "taxRegistrationNumber"  => $this->documento,
            "requestID"  => $this->requestID,
        ];
        
        $jwsSignature = JWT::encode($payloadJwsSignature, $privateKey, 'RS256');

        $payload = [
            "productId" => "ANGOSIS-LD",
            "productVersion" => "1.0.1",
            "softwareValidationNumber" => "C_250"
        ];
        
        $jwsSoftwareSignature = $this->gerarJwtRs256(
            $payload,
            storage_path('app/public/keys/private.pem')
        );
        
        $invoice = [
            'schemaVersion' => '1.0',
            'submissionUUID' => Str::uuid()->toString(),
            'taxRegistrationNumber' => $this->documento,
            'submissionTimeStamp' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'softwareInfo' => [
                'softwareInfoDetail' => $this->payloadHeader,
                'jwsSoftwareSignature' => 'eyJ0eXAiOiJKT1NFIiwiYWxnIjoiUlMyNTYifQ.eyJwcm9kdWN0SWQiOiJNZXUgRVJQIENFUlRPIiwicHJvZHVjdFZlcnNpb24iOiIxLjAuMSIsInNvZnR3YXJlVmFsaWRhdGlvbk51bWJlciI6IkNfMTM0In0.VE3zkvOJOpqBfz4wpx4KCgcwOGgzGUP3MSMbaHCDnHhwOaeA6jlccBW9HjgQvg2tYCVVq0imrU_z0grEHNthhG4xD3afSOD1_RzvHs8Tc45dvztHJzB4gF0CAX-yIDwi7XcHiMRY0vkXOETBeHKewg0ktWSnZ7SLf4GxGzE7ry2u_pmhqhCPhxpa0oGQ_rBJUYkEAFg1OwaqjwvzCFgdT11r-XsHmnkcfJX_ktj59RWR_zgbytiCRtwCK9LNUflveS5GzUaCXbPn2deQ3F2hPldLECEa_ahwoapoK1LhkgOAVyPLJf6M1Cm09Le7rkdSaWQSW5BI_sPx5YaUaXkeqg',
            ],
            'requestID' => $this->requestID
        ];
            
                
        try {
            $client = new \GuzzleHttp\Client();
        
            $response = $client->post(
                'https://sifphml.minfin.gov.ao/sigt/fe/v1/obterEstado',
                [
                    'auth' => ['ws.hml.asis', 'mfn20162025'],
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'json' => $invoice,
                ]
            );
        
            return response()->json([
                'success' => true,
                'response' => json_decode($response->getBody(), true),
            ]);
        
        } catch (RequestException $e) {
        
            return response()->json([
                'success' => false,
                'status' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                'error' => $e->hasResponse()
                    ? json_decode($e->getResponse()->getBody(), true)
                    : $e->getMessage(),
            ], 500);
        }
    }
    
    
    public function consultarFatura(Request $reques)
    {
        $privateKeyPath = storage_path('app/public/keys/private.pem');
        
        if (!file_exists($privateKeyPath)) {
            dd("Arquivo não encontrado: " . $privateKeyPath);
        }

        $privateKey = file_get_contents($privateKeyPath);
        
        $payloadJwsSignature = [
            "taxRegistrationNumber"  => $this->documento,
            "invoiceNo"  => $this->factura,
        ];
        
        $jwsSignature = $this->generateJwt($payloadJwsSignature);
        
        $invoice = [
            'schemaVersion' => '1.0',
            'submissionUUID' => Str::uuid()->toString(),
            'taxRegistrationNumber' => $this->documento,
            'submissionTimeStamp' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'softwareInfo' => [
                'softwareInfoDetail' => $this->payloadHeader,
                'jwsSoftwareSignature' => 'eyJ0eXAiOiJKT1NFIiwiYWxnIjoiUlMyNTYifQ.eyJwcm9kdWN0SWQiOiJNZXUgRVJQIENFUlRPIiwicHJvZHVjdFZlcnNpb24iOiIxLjAuMSIsInNvZnR3YXJlVmFsaWRhdGlvbk51bWJlciI6IkNfMTM0In0.VE3zkvOJOpqBfz4wpx4KCgcwOGgzGUP3MSMbaHCDnHhwOaeA6jlccBW9HjgQvg2tYCVVq0imrU_z0grEHNthhG4xD3afSOD1_RzvHs8Tc45dvztHJzB4gF0CAX-yIDwi7XcHiMRY0vkXOETBeHKewg0ktWSnZ7SLf4GxGzE7ry2u_pmhqhCPhxpa0oGQ_rBJUYkEAFg1OwaqjwvzCFgdT11r-XsHmnkcfJX_ktj59RWR_zgbytiCRtwCK9LNUflveS5GzUaCXbPn2deQ3F2hPldLECEa_ahwoapoK1LhkgOAVyPLJf6M1Cm09Le7rkdSaWQSW5BI_sPx5YaUaXkeqg',
            ],
            'jwsSignature' => $jwsSignature,
            'invoiceNo' => $this->factura
        ];
                
        try {
            $client = new \GuzzleHttp\Client();
        
            $response = $client->post(
                'https://sifphml.minfin.gov.ao/sigt/fe/v1/consultarFactura',
                [
                    'auth' => ['ws.hml.asis', 'mfn20162025'],
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'json' => $invoice,
                ]
            );
        
            return response()->json([
                'success' => true,
                'response' => json_decode($response->getBody(), true),
            ]);
        
        } catch (RequestException $e) {
        
            return response()->json([
                'success' => false,
                'status' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                'error' => $e->hasResponse()
                    ? json_decode($e->getResponse()->getBody(), true)
                    : $e->getMessage(),
            ], 500);
        }
    }
    
    
    public function listarFacturas(Request $reques)
    {
      
        $privateKeyPath = storage_path('app/public/keys/private.pem');
        
        if (!file_exists($privateKeyPath)) {
            dd("Arquivo não encontrado: " . $privateKeyPath);
        }

        $privateKey = file_get_contents($privateKeyPath);
        
        $payloadJwsSignature = [
            "taxRegistrationNumber"  => $this->documento,
            "queryStartDate"  => $this->queryStartDate,
            "queryEndDate"  => $this->queryEndDate,
        ];
        
        $jwsSignature = $this->generateJwt($payloadJwsSignature);
        
        $invoice = [
            'schemaVersion' => '1.0',
            'submissionUUID' => Str::uuid()->toString(),
            'taxRegistrationNumber' => $this->documento,
            'submissionTimeStamp' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'softwareInfo' => [
                'softwareInfoDetail' => $this->payloadHeader,
                'jwsSoftwareSignature' => 'eyJ0eXAiOiJKT1NFIiwiYWxnIjoiUlMyNTYifQ.eyJwcm9kdWN0SWQiOiJNZXUgRVJQIENFUlRPIiwicHJvZHVjdFZlcnNpb24iOiIxLjAuMSIsInNvZnR3YXJlVmFsaWRhdGlvbk51bWJlciI6IkNfMTM0In0.VE3zkvOJOpqBfz4wpx4KCgcwOGgzGUP3MSMbaHCDnHhwOaeA6jlccBW9HjgQvg2tYCVVq0imrU_z0grEHNthhG4xD3afSOD1_RzvHs8Tc45dvztHJzB4gF0CAX-yIDwi7XcHiMRY0vkXOETBeHKewg0ktWSnZ7SLf4GxGzE7ry2u_pmhqhCPhxpa0oGQ_rBJUYkEAFg1OwaqjwvzCFgdT11r-XsHmnkcfJX_ktj59RWR_zgbytiCRtwCK9LNUflveS5GzUaCXbPn2deQ3F2hPldLECEa_ahwoapoK1LhkgOAVyPLJf6M1Cm09Le7rkdSaWQSW5BI_sPx5YaUaXkeqg',
            ],
            'jwsSignature' => $jwsSignature,
            'queryStartDate' => $this->queryStartDate,
            'queryEndDate' => $this->queryEndDate
        ];
            
                
        try {
            $client = new \GuzzleHttp\Client();
        
            $response = $client->post(
                'https://sifphml.minfin.gov.ao/sigt/fe/v1/listarFacturas',
                [
                    'auth' => ['ws.hml.asis', 'mfn20162025'],
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'json' => $invoice,
                ]
            );
        
            return response()->json([
                'success' => true,
                'response' => json_decode($response->getBody(), true),
            ]);
        
        } catch (RequestException $e) {
        
            return response()->json([
                'success' => false,
                'status' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                'error' => $e->hasResponse()
                    ? json_decode($e->getResponse()->getBody(), true)
                    : $e->getMessage(),
            ], 500);
        }
    }
    

    public static function generateJwt(array $payload): string
    {
        $privateKeyPath = storage_path('app/public/keys/private.pem');

        if (!file_exists($privateKeyPath)) {
            throw new \Exception('Chave privada não encontrada');
        }

        $privateKey = file_get_contents($privateKeyPath);

        // Header fixo conforme especificação
        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        // Função Base64URL
        $base64UrlEncode = function ($data) {
            return rtrim(
                strtr(base64_encode(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)), '+/', '-_'),
                '='
            );
        };

        // Codificar header e payload
        $encodedHeader  = $base64UrlEncode($header);
        $encodedPayload = $base64UrlEncode($payload);

        // Dados a assinar (JWS Compact Serialization)
        $dataToSign = $encodedHeader . '.' . $encodedPayload;

        // Assinar com RS256
        openssl_sign(
            $dataToSign,
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );

        // Signature Base64URL
        $encodedSignature = rtrim(
            strtr(base64_encode($signature), '+/', '-_'),
            '='
        );

        // JWT final
        return $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
    }

    
    function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function gerarJwtRs256(array $payload, string $privateKeyPath): string
    {
        // 1️⃣ HEADER FIXO (RS256)
        $header = [
            "typ" => "JOSE",
            "alg" => "RS256"
        ];

        // 2️⃣ JSON → Base64URL
        $headerEncoded  = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        // 3️⃣ STRING A ASSINAR
        $dataToSign = $headerEncoded . "." . $payloadEncoded;

        // 4️⃣ LER CHAVE PRIVADA
        $privateKey = file_get_contents($privateKeyPath);

        // 5️⃣ ASSINAR COM RSA + SHA256
        openssl_sign(
            $dataToSign,
            $signature,
            $privateKey,
            OPENSSL_ALGO_SHA256
        );

        // 6️⃣ ASSINATURA BASE64URL
        $signatureEncoded = $this->base64UrlEncode($signature);

        // 7️⃣ JWT FINAL
        return $dataToSign . "." . $signatureEncoded;
    }


}
