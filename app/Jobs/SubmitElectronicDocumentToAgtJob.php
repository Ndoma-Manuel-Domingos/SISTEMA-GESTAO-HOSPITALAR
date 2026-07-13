<?php

namespace App\Jobs;

use App\Models\Entidade;
use App\Models\Venda;
use App\Support\MoneyAgt;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\UsesAgtConfig;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubmitElectronicDocumentToAgtJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, UsesAgtConfig;

    public int $tries = 4;
    public array $backoff = [30, 60, 120];
    public int $timeout = 60;

    private string $faturaId;
    private bool $nc = false;


    public function __construct(string $faturaId, $nc = false)
    {
        $this->faturaId = $faturaId;
        $this->nc = $nc;
        
        // dd("TESTE");

        // $this->loadAgtConfig();
        // $invoice = $this->loadInvoice();
        // $nowUtc  = Carbon::now('UTC');

        // $document = $this->buildDocument($invoice, $nowUtc);
        // $payload  = $this->buildPayload($document, $nowUtc, $invoice);

        // $this->submit($payload, $invoice->id);
    }

    public function handle(): void
    {
        $this->loadAgtConfig();
        $invoice = $this->loadInvoice();
        
        $nowUtc  = Carbon::now('UTC');
        $document = $this->buildDocument($invoice, $nowUtc);
        $payload  = $this->buildPayload($document, $nowUtc, $invoice);
        $this->submit($payload, $invoice->id);
    }

    private function loadInvoice(): Venda
    {
        return Venda::with(['items', 'notaCredito', 'items.produto.taxa_imposto', 'items.produto.motivo'])
            ->findOrFail($this->faturaId);
    }

    private function buildDocument(Venda $invoice, Carbon $nowUtc): array
    {
        $lines      = [];
        $taxPayable = '0.00';
        $netTotal   = '0.00';

        foreach ($invoice->items as $index => $item) {
            $line = $this->buildLine($item, $index + 1, $invoice);
            
            // tem mais de duas casas decimais
            if (preg_match('/\.\d{3,}/', $line['taxContribution'])) {
                $taxPayable = bcadd($taxPayable, MoneyAgt::ceil($line['taxContribution'], 2), 2);
            } else {
                $taxPayable = bcadd($taxPayable, $line['taxContribution'], 4);
            }

            $netTotal = bcadd($netTotal, $line['creditAmount'], 2);

            $lines[] = $line['payload'];
        }

        $grossTotal = bcadd($taxPayable, $netTotal, 2);
  
        return [
            'lines'       => $lines,
            'taxPayable'  => $taxPayable,
            'netTotal'    => $netTotal,
            'grossTotal'  => $grossTotal,
        ];
    }

    private function buildLine($item, int $lineNumber, $invoice): array
    {
        $quantity = (string) ($item->quantidade ?? 1);

        // $unitPriceBase = bcdiv((string)$item->valor_total, $quantity, 4);
        $unitPriceBase = (string) ($item->valor_base ?? 0);

        $discount = (string) ($item->desconto_aplicado_valor ?? 0);
        // $discount = $item->valorDesconto > 0 ? (string) $item->valorDesconto : bcmul((string) $item->total, bcdiv((string) $item->desconto, '100', 4), 2);

        // $unitPrice = bcdiv(bcsub((string) $item->total, $discount, 4), $quantity, 4);
        $unitPrice =  $unitPriceBase - $discount;

        // $creditAmount = MoneyAgt::ceil(bcmul($unitPrice, $quantity, 4), 2);
        $creditAmount = MoneyAgt::ceil(bcmul($unitPrice, $quantity, 4), 2);

        $taxContribution = $item->valor_iva;

        $data =  [
            'creditAmount'   => $creditAmount,
            'taxContribution' => $taxContribution,
            'payload' => [
                'lineNumber' => $lineNumber,
                'operationType' => 'SG',
                'productCode' => $item->produto->id,
                'productDescription' => $item->produto->nome ?? "",
                'quantity' => (float) $quantity,
                'unitOfMeasure' => 'UN',
                'unitPriceBase' => $unitPriceBase,
                'unitPrice' => $item->desconto_aplicado == 100 ? 0 : $unitPrice,
                'debitAmount' => $this->nc ? $creditAmount : "0.00",
                'creditAmount' => $this->nc ? "0.00" : $creditAmount,
                "referenceInfo" => [],
                'taxes' => [[
                    'taxType' => $item->iva_taxa ? 'IVA' : 'IS',
                    'taxCountryRegion' => 'AO',
                    'taxCode' => $item->iva_taxa ? 'NOR' : 'ISE',
                    'taxPercentage' => (int) $item->iva_taxa,
                    'taxContribution' => $taxContribution,
                ]],
                'settlementAmount' => $discount,
            ],
        ];
        
        if ($this->nc) {
            array_push($data['payload']['referenceInfo'], [
                "reference" => $invoice['factura_next'],
                "reason" => $invoice['notaCredito']['observacao'] ?? "Motivo não especificado",
                "referenceItemLineNo" => $lineNumber
            ]);
        }
        return $data;
    }

    private function buildPayload(array $document, Carbon $nowUtc, $invoice): array
    {
        $entidade = Entidade::findOrFail($invoice->entidade_id);
        
        $taxRegistrationNumber = $entidade->nif ?? NULL; 
        $privateCustomerKey = $entidade->private_key ?? NULL;
    
        $submissionUUID = Str::uuid()->toString();
        $documentSignature = JWT::encode($this->utf8ize([
            'documentNo' => $this->nc ? $invoice['notaCredito']['factura_next'] : $invoice['factura_next'],
            'taxRegistrationNumber' => $taxRegistrationNumber,
            // 'taxRegistrationNumber' => $this->taxRegistrationNumber,
            'documentType' => $this->nc ? "NC" : ($invoice['factura']),
            'documentDate' => $this->nc ? Carbon::parse($invoice['notaCredito']['created_at'])->format('Y-m-d') : Carbon::parse($invoice['created_at'])->format('Y-m-d'),
            'customerTaxID' => $invoice['nif_cliente'],
            'customerCountry' => 'AO',
            'companyName' => $invoice['nome_cliente'],
            'documentTotals' => [
                'taxPayable' => $document['taxPayable'],
                'netTotal'   => $document['netTotal'],
                'grossTotal' => $document['grossTotal'],
            ]
        ]), 
        $privateCustomerKey, 
        // $this->privateCustomerKey, 
        'RS256');

        $data =  [
            'schemaVersion' => $this->schemaVersion(),
            'submissionUUID' => $submissionUUID,
            'taxRegistrationNumber' => $taxRegistrationNumber,
            // 'taxRegistrationNumber' => $this->taxRegistrationNumber,
            'submissionTimeStamp' => $nowUtc->format('Y-m-d\TH:i:s\Z'),
            'softwareInfo' => [
                'softwareInfoDetail' => $this->softwareInfoDetail(),
                'jwsSoftwareSignature' => $this->jwsSoftwareSignature(),
            ],
            'numberOfEntries' => 1,
            'documents' => [[
                'documentNo' => $this->nc ? $invoice['notaCredito']['factura_next'] : $invoice['factura_next'],
                'documentStatus' => 'N',
                'jwsDocumentSignature' => $documentSignature,
                'documentDate' => $this->nc ? Carbon::parse($invoice['notaCredito']['created_at'])->format('Y-m-d') : Carbon::parse($invoice['created_at'])->format('Y-m-d'),
                'documentType' => $this->nc ? "NC" : ($invoice['factura']),
                'systemEntryDate' => $nowUtc->format('Y-m-d\TH:i:s\Z'),
                'customerTaxID' => $invoice['nif_cliente'],
                'customerCountry' => 'AO',
                'companyName' => $invoice['nome_cliente'],
                'lines' => $document['lines'],
                'documentTotals' => [
                    'taxPayable' => $document['taxPayable'],
                    'netTotal' => $document['netTotal'],
                    'grossTotal' => $document['grossTotal'],
                ],
                "withholdingTaxList" => []
            ]],
        ];

        if ($invoice['total_retencao_fonte'] > 0) {
            array_push($data['documents'][0]['withholdingTaxList'], [
                "withholdingTaxType" => "IRT",
                "withholdingTaxDescription" => "Retenção na fonte",
                "withholdingTaxAmount" => $invoice['total_retencao_fonte'] > 0 ? $invoice['total_retencao_fonte'] : "0.00"
            ]);
        }
        if ($invoice['valor_imposto_predial'] > 0) {
            array_push($data['documents'][0]['withholdingTaxList'], [
                "withholdingTaxType" => "IP",
                "withholdingTaxDescription" => "Imposto Predial",
                "withholdingTaxAmount" => $invoice['valor_imposto_predial'] > 0 ? $invoice['valor_imposto_predial'] : "0.00"
            ]);
        }
        return $data;
    }

    private function submit(array $payload, string $faturaId): void
    {
        try {
            $response = $this->submitFiscalDocument($payload, '/registarFactura', $faturaId);
            $requestID = $response->original['response']['requestID'];
            if ($payload['documents'][0]['documentType'] == "NC") {
                DB::table('notas_reditos')->where('id', $faturaId)->update([
                    'requestID' => $requestID
                ]);
            } else {
                DB::table('vendas')->where('id', $faturaId)->update([
                    'requestID' => $requestID
                ]);
            }

            Log::info('AGT fatura registada', [
                'documentId' => $faturaId,
                'response' => $response,
            ]);
        } catch (RequestException $e) {
            Log::error('Erro AGT', [
                'documentId' => $faturaId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
