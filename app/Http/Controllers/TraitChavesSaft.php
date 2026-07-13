<?php

namespace App\Http\Controllers;

Trait TraitChavesSaft{
    public function pegarChavePrivada()
    {
        // EA-VEIGAS
    
        $privatekey = "MIICXQIBAAKBgQDlXVtFxC1Il/IA0ikvRYXTVT8YxIBjWbeU7y3SdTVgTmvqjkqu
        S5Jh+VM+lYv/41wmtB2+/XmppGiSEO+4RuG7D+EVgukQ97sDWqx1OjpGmq+aViBX
        lgjgId1iXiL31YdWq/62NRuv978dsIPajIgr6scQLTkyBJfotjV7nRah4wIDAQAB
        AoGAN/GiXozwAqHVDDA2jWomrxo+zpq3OgRbC7+e7JNcFoZqOgbE3mheZvk6Paya
        PirFgkcybjBDKYaJXv80iTz4t4J5LFXtJs450zHnkrY36XlLSe1p+QGnyNx3i8n1
        JDz2VEmC34AtkpCxgGMkZ2wIuHvzRwjAQ8KIoIkGjNb360kCQQD2mq1urmyXXa6t
        aYv7RpbJLGKLVX0qn/6jsh1F/ReckeJutVb7u4PKf+NqNXdhXtKt0wtgzmwmrixM
        /Oqawsf/AkEA7hqHSmqD6G8d4LSPTxXFjCfExu8zV0QiVWd/jpy0QnZvW30FK77n
        zeyvWZ+nj8wuUriu8Qnj0XJ4l86pS6oGHQJBANi0bknAH48YdSLQiIFks6bPST21
        /0sQ1B0XrV/OnAwrqrasxmZqjtLJdZfkqia3xB2aQvpsC2AmWKnC64raNhMCQCxi
        w5+qtYZJ2H8ACcsLWvUioLsY8jAtYl0bWxsBuVS+cUnTx3f9MYcgvRtu+LSEson3
        JZ2HY3Gy7ioWe1bAjj0CQQDgYKL5xpYoBAg6dSpgZywksx+L4hAI99UGNcirWhM7
        gSNtTV8Lt7X9fNPBMkrKZBBedHkDTEEMtzeUPOTdgwD2";
        
        // // ANGOENGENHARIA
        // $privatekey = "MIICXQIBAAKBgQDvvidjJN/x97c+hLMTB5a/QiGY4PY/gJofe8Ewk9usaAoIsScP
        // XXTtzYdNwltLIT6myjmgZex1ZKbJvY45z57rWU2H7To905rEdLGukUWLU+Ih5j5y
        // Cu2o6ZbhreCS8lms++CD224kdst0ERkPs6gp7jLtoSngeSjDtmFEz27N7wIDAQAB
        // AoGBAKr2cEIMVsLHgt5LZSQp4j9LmofZzODmBYJ0DwVkO2AL5TPjmNYlMDAww88Y
        // zV+bOoFYbpXKeUAR4bq1uUnSnCEZbWUIxt3IA586AEXVlTfkEem3yAmijs3KsomM
        // TgdSCB1bjsciyJxaEQugAN2TtLkuGVbjAxxeLqcGWT0vgPARAkEA/HQlo1STS2Pe
        // E0vzYOaqfGoiVRMS9nz37dsRuzqYw63oBxlTtHziT6gxuwGHlmqkAKbk9S3bJG5M
        // CI4Ql3iTaQJBAPMcTAKLlRk5JPtKWsbgHGTBhsvrROd3R3KFZql2lLJeZ+AhxNdN
        // ugMWPB/Tw5OxnM8ozjepARm//OWBG1tUo5cCQFYo0bunnsmpIN7XGg4lS2RA2MzO
        // QLeNORSdorSSsBaAoOLjWvULdjWXgrl/MSY96REr5JJk/xi06BA3ZCQ+C5kCQQCE
        // QlK/17xHuqT56Ru1slsAHaD984LLAkNmdFjDvPVsjJuqffSexXR7FW+kSQrPoQMU
        // WLjH4zPsFFy7Zx/A8i0FAkBfzoEsHPDVfxSonO8R7fhW3BVedah594OJU8nabwL5
        // 7OPqvgAn6ssgtD038KdGHZFefuXDTEohO8kYxSkPUXyZ";
        

        return $privatekey;
    }
    public function pegarChavePublica()
    {
        
        // EA-VEIGAS
        
        $publickey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDlXVtFxC1Il/IA0ikvRYXTVT8Y
        xIBjWbeU7y3SdTVgTmvqjkquS5Jh+VM+lYv/41wmtB2+/XmppGiSEO+4RuG7D+EV
        gukQ97sDWqx1OjpGmq+aViBXlgjgId1iXiL31YdWq/62NRuv978dsIPajIgr6scQ
        LTkyBJfotjV7nRah4wIDAQAB";
  
  
        // ANGOENGENHARIA
        // $publickey = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDvvidjJN/x97c+hLMTB5a/QiGY
        // 4PY/gJofe8Ewk9usaAoIsScPXXTtzYdNwltLIT6myjmgZex1ZKbJvY45z57rWU2H
        // 7To905rEdLGukUWLU+Ih5j5yCu2o6ZbhreCS8lms++CD224kdst0ERkPs6gp7jLt
        // oSngeSjDtmFEz27N7wIDAQAB";

        return $publickey;
    }

    
    function getMachineFingerprint()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $cpu = shell_exec('wmic cpu get ProcessorId');
            $disk = shell_exec('wmic diskdrive get SerialNumber');
            $mac = shell_exec('getmac');
        } else {
            $cpu = shell_exec("cat /proc/cpuinfo | grep Serial | head -n 1");
            $disk = shell_exec("lsblk -o SERIAL");
            $mac = shell_exec("ip link");
        }

        return hash('sha256', $cpu . $disk);
        // return hash('sha256', $cpu . $disk . $mac);
    }


}