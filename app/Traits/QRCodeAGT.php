<?php

namespace App\Traits;

use App\Models\Entidade;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

trait QRCodeAGT
{
    use UsesAgtConfig;
    
    public function generateQRcode(string $documentNo, string $entidadeId): string
    {
        $entidade = Entidade::findOrFail($entidadeId);
        
        $taxRegistrationNumber = $entidade->nif ?? NULL; 
    
        $this->loadAgtConfig();
        // Link da AGT
        $linkAGT = "https://quiosqueagt.minfin.gov.ao/facturacao-eletronica/consultar-fe?emissor={$taxRegistrationNumber}&document={$documentNo}";
        // $linkAGT = "https://quiosqueagt.minfin.gov.ao/facturacao-eletronica/consultar-fe?emissor={$this->taxRegistrationNumber}&document={$documentNo}";
        $writer = new PngWriter();
        // Create QR code
        $qrCode = new QrCode(
            data: $linkAGT,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 350,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );
        // Create generic logo
        $logo = new Logo(
            path: public_path() . '/upload/logoAGT.png',
            resizeToWidth: 50,
            punchoutBackground: true
        );
        $result = $writer->write($qrCode, $logo);
        //   header('Content-Type: ' . $result->getMimeType());
        $qrCodeBytes = $result->getString();
        $qrCodeBase64 = base64_encode($qrCodeBytes);
        return $qrCodeBase64;
    }
    
    
}