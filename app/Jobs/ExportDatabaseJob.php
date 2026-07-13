<?php

namespace App\Jobs;

use App\Models\BackupSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Symfony\Component\Process\Process;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Exception;

class ExportDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $settingId;
    public $dbName;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($settingId, $dbName)
    {
        $this->settingId = $settingId;
        $this->dbName = $dbName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $setting = BackupSetting::find($this->settingId);
        if (!$setting || !$setting->enabled) {
            return;
        }
      
        $folder = rtrim($setting->folder_path, DIRECTORY_SEPARATOR);
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
      
        // pega credenciais do connection mysql
        $conn = config('database.connections.mysql');
        $user = $conn['username'] ?? 'root';
        $pass = $conn['password'] ?? '';
        $host = $conn['host'] ?? '127.0.0.1';
        $port = $conn['port'] ?? 3306;
            
        // Detecta o caminho do mysqldump automaticamente
        $mysqlDumpPath = trim(shell_exec('where mysqldump'));
        
        if (!$mysqlDumpPath || !file_exists($mysqlDumpPath)) {
            return back()->with('error', 'mysqldump não encontrado. Verifique se o MySQL está instalado e adicionado ao PATH.');
        }

        $timestamp = date('Y-m-d_H-i-s');
        $sqlFile = $folder . DIRECTORY_SEPARATOR . "{$this->dbName}_{$timestamp}.sql";
        
        $command = "\"{$mysqlDumpPath}\" --user={$user} --password={$pass} --host={$host} --port={$port} {$this->dbName} > \"{$sqlFile}\"";
        exec($command, $output, $resultCode);
        
        if ($resultCode !== 0) {
            return back()->with('error', "Erro ao exportar banco de dados. Código de erro: {$resultCode}");
        }
    
    }
    
    function encontrarMysqldumpMaisProximo($baseDir, $versaoAlvo)
    {
        $versaoAlvo = str_replace('mysql-', '', $versaoAlvo); // exemplo: 8.0.31-winx64
        $dirs = glob($baseDir . '\\mysql-*', GLOB_ONLYDIR);
    
        if (!$dirs) {
            echo "❌ Nenhuma instalação MySQL encontrada em $baseDir\n";
            return null;
        }
    
        $encontrados = [];
    
        foreach ($dirs as $dir) {
            $mysqldumpPath = $dir . '\\bin\\mysqldump.exe';
            if (file_exists($mysqldumpPath)) {
                // Extrai a versão do nome da pasta
                preg_match('/mysql\-([0-9\.]+)\-winx64/', $dir, $matches);
                $versao = $matches[1] ?? '0.0.0';
                $encontrados[$versao] = $mysqldumpPath;
            }
        }
    
        if (empty($encontrados)) {
            // echo "❌ Nenhum mysqldump.exe encontrado em versões instaladas.\n";
            return null;
        }
    
        // Ordena pelas versões mais próximas
        uksort($encontrados, function($a, $b) use ($versaoAlvo) {
            return version_compare($a, $b);
        });
    
        // echo "📦 Mysqldump(s) encontrados:\n";
        // foreach ($encontrados as $versao => $caminho) {
        //     // echo " - [$versao] $caminho\n";
        // }
    
        // Verifica se a versão alvo existe exatamente
        if (isset($encontrados[$versaoAlvo])) {
            // echo "\n✅ Caminho exato encontrado: " . $encontrados[$versaoAlvo] . "\n";
            return $encontrados[$versaoAlvo];
        }
    
        // Se não, retorna a versão mais próxima (última da lista)
        $maisProxima = end($encontrados);
        // echo "\n⚠️ Versão exata não encontrada. Usando a mais próxima: $maisProxima\n";
        return $maisProxima;
    }

    
}
