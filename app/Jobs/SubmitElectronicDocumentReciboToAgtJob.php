<?php

namespace App\Jobs;

use App\Models\Entidade;
use App\Models\Recibo;
use App\Support\MoneyAgt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Exception\RequestException;
use App\Traits\UsesAgtConfig;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubmitElectronicDocumentReciboToAgtJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UsesAgtConfig;

    public int $tries = 2;
    public array $backoff = [30, 60, 120];
    public int $timeout = 60;

    private string $reciboId;

    public function __construct(string $reciboId)
    {
        $this->reciboId = $reciboId;

        // $this->loadAgtConfig();
        // $receipt = $this->loadReceipt();
        // $nowUtc  = Carbon::now('UTC');

        // $document = $this->buildDocument($receipt, $nowUtc);
        // $payload  = $this->buildPayload($receipt, $nowUtc);

        // $this->submit($payload, $receipt->id);
    }


    public function handle(): void
    {
        $receipt = $this->loadReceipt();
        $this->loadAgtConfig();

        $nowUtc  = Carbon::now('UTC');

        // $document = $this->buildDocument($receipt, $nowUtc);
        $payload  = $this->buildPayload($receipt, $nowUtc);

        $this->submit($payload, $receipt->id);
    }


    /* ============================================================
     *  DATA
     * ============================================================
     */

    private function loadReceipt(): Recibo
    {
        return Recibo::with(['facturas', 'facturas.items'])->findOrFail($this->reciboId);
    }

    private function buildPayload($receipt, Carbon $nowUtc): array
    {
        $entidade = Entidade::findOrFail($receipt->entidade_id);
        
        $taxRegistrationNumber = $entidade->nif ?? NULL; 
        $privateCustomerKey = $entidade->private_key ?? NULL;
    
        $taxaIva = (($receipt['facturas']['total_iva'] / 100) + 1);
        $netTotal =  MoneyAgt::ceil(bcdiv($receipt['valor_entregue'], $taxaIva, 2));
        $taxPayable = MoneyAgt::ceil($receipt['valor_entregue'] - $netTotal);
        $grossTotal = MoneyAgt::ceil($netTotal + $taxPayable);

        $documentSignature = JWT::encode($this->utf8ize([
            'documentNo' => $receipt['factura_next'],
            'taxRegistrationNumber' => $taxRegistrationNumber,
            // 'taxRegistrationNumber' => $this->taxRegistrationNumber,
            'documentType' => "RG",
            'documentDate' => Carbon::parse($receipt['created_at'])->format('Y-m-d'),
            'customerTaxID' => $receipt['facturas']['documento_nif'],
            'customerCountry' => 'AO',
            'companyName' => $receipt['facturas']['nome_cliente'],
            'documentTotals' => [
                'taxPayable' => $taxPayable,
                'netTotal'   => $netTotal,
                'grossTotal' => $grossTotal,
            ],
        ]), 
        $privateCustomerKey, 
        // $this->privateCustomerKey, 
        'RS256');

        $payload = [
            'schemaVersion' => $this->schemaVersion(),
            'submissionUUID' => Str::uuid()->toString(),
            'taxRegistrationNumber' => $taxRegistrationNumber,
            // 'taxRegistrationNumber' => $this->taxRegistrationNumber,
            'submissionTimeStamp' => $nowUtc->format('Y-m-d\TH:i:s\Z'),
            'softwareInfo' => [
                'softwareInfoDetail' => $this->softwareInfoDetail(),
                'jwsSoftwareSignature' => $this->jwsSoftwareSignature()
            ],

            'numberOfEntries' => 1,
            'documents' => [
                [
                    'documentNo' => $receipt['factura_next'],
                    'documentStatus' => 'N',
                    'jwsDocumentSignature' => $documentSignature,
                    'documentDate' => Carbon::parse($receipt['created_at'])->format('Y-m-d'),
                    'documentType' => 'RG',
                    'eacCode' => '58200',
                    'systemEntryDate' => Carbon::now()->format('Y-m-d\TH:i:s'),
                    'customerTaxID' => $receipt['facturas']['documento_nif'],
                    'customerCountry' => 'AO',
                    'companyName' => $receipt['facturas']['nome_cliente'],
                    'paymentReceipt' => [
                        'sourceDocuments' => [
                            [
                                'lineNo' => 1,
                                'sourceDocumentID' => [
                                    'originatingON' => $receipt['facturas']['factura_next'],
                                    'documentDate' => Carbon::parse($receipt['created_at'])->format('Y-m-d'),
                                ],
                                'debitAmount' => 0.0,
                                'creditAmount' => $netTotal,
                            ],
                        ],
                    ],

                    'documentTotals' => [
                        'taxPayable' => $taxPayable,
                        'netTotal' => $netTotal,
                        'grossTotal' => $grossTotal,
                    ],
                ],
            ],
        ];

        return $payload;
    }


    private function submit(array $payload, string $reciboId): void
    {
        try {
            $response = $this->submitFiscalDocument($payload, '/registarFactura', $reciboId);
            $requestID = $response->original['response']['requestID'];
            DB::table('recibos')->where('id', $reciboId)->update([
                'requestID' => $requestID
            ]);
            Log::info('AGT recibo registado', [
                'documentId' => $reciboId,
                'response' => $response,
            ]);
        } catch (RequestException $e) {
            Log::error('Erro AGT', [
                'documentId' => $reciboId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
