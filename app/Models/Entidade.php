<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entidade extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nif',
        'nome',
        'establishment_number',
        'tipo_facturacao',
        'modelo_factura',
        'private_key',
        'public_key',
        'sigla',
        'tipo_id',
        'plano_id',
        'membro_id',
        'municipio_id',
        'provincia_id',
        'status',
        'tipo_empresa',
        'morada',
        'numero_via_documento',
        'tipo_factura',
        'codigo_postal',
        'cidade',
        'conservatoria',
        'capital_social',
        'data_inicio_actividade',
        'ano_inicio_actividade',
        'nome_comercial',
        'slogan', // será subistituido por numero de vias de factura a serem impresso
        'logotipo',
        'pais',
        'moeda',
        'taxa_iva',
        'motivo_isencao',
        'telefone',
        'telemovel',
        'tipo_inventario',
        'destino_pedidos',
        'tipo_venda',
        'tipo_pronto_venda',
        'exibicao_relatorio', //sintetico // detalhado
        'email',
        'fax',
        'website',
        'level',
        'banco',
        'conta',
        'iban',
        'banco1',
        'conta1',
        'iban1',
        'inicializacao',
        'nome_cliente',
        'documento_nif',
        'tipo_regime_id',
        'imposto_id',
        'motivo_id',
        'finalizacao',
        'cabecalho',
        'rodape',
        'finalizacao_venda',
        'promocoes_email',
        'novidade_email',
        'first_login_system',
        'marca_d_agua_facturas',
        'sigla_factura',
        'ano_factura',
        'taxa_retencao_fonte',
        'valor_taxa_retencao_fonte'
    ];


    public function operacoes_financeiras()
    {
        return $this->hasMany(OperacaoFinanceiro::class, 'entidade_id', 'id');
    }

    public function facturamentos()
    {
        return $this->hasMany(OperacaoFinanceiro::class, 'entidade_id', 'id');
    }

    public function getTotalEntradasAttribute()
    {
        return $this->facturamentos
            ->where('type', 'R')
            ->sum('motante');
    }

    public function getTotalSaidasAttribute()
    {
        return $this->facturamentos
            ->where('type', 'D')
            ->sum('motante');
    }

    public function getSaldoAttribute()
    {
        return $this->total_entradas - $this->total_saidas;
    }

    public function empresa_modulos()
    {
        return $this->belongsToMany(Modulo::class, 'modulo_entidade');
    }

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id', 'id');
    }

    public function mensalidades()
    {
        return $this->hasMany(Mensalidade::class, 'entidade_id', 'id');
    }

    public function pagamentos()
    {
        return $this->hasMany(Pagamento::class, 'entidade_id', 'id');
    }

    public function taxa_imposto()
    {
        return $this->belongsTo(Imposto::class, 'imposto_id', 'id');
    }

    public function motivo()
    {
        return $this->belongsTo(Motivo::class, 'motivo_id', 'id');
    }

    public function controle()
    {
        return $this->hasOne(ControloSistema::class);
    }

    public function tipo_entidade()
    {
        return $this->hasOne(TipoEntidade::class, 'id', 'tipo_id');
    }

    public function configuracao_impressora()
    {
        return $this->hasOne(ConfiguracaoEmpressora::class, 'id', 'entidade_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function marcas()
    {
        return $this->hasMany(Marca::class);
    }

    public function variacoes()
    {
        return $this->hasMany(Variacao::class);
    }

    public function caixas()
    {
        return $this->hasMany(Caixa::class);
    }

    public function lojas()
    {
        return $this->hasMany(Loja::class, 'entidade_id', 'id');
    }

    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function estoques()
    {
        return $this->hasMany(Estoque::class);
    }

    /**
     * Gera uma sigla única de 3 letras.
     */
    public static function generateUniqueSigla()
    {
        do {
            // Gerar uma sigla aleatória de 3 letras maiúsculas
            $sigla = strtoupper(Str::random(3)); // Exemplo: 'ABC'
        } while (self::where('sigla', $sigla)->exists()); // Verifica se a sigla já existe

        return $sigla;
    }

    public function dias_licencas($id)
    {
        date_default_timezone_set('Africa/Luanda');
        /*sistema de datas*/
        $dia = @date("d");
        $mes = @date("m");
        $ano = @date("Y");
        $dataFinal = $ano . "-" . $mes . "-" . $dia;

        $controlo = Entidade::with(['tipo_entidade', 'controle'])->findOrFail($id);

        $diasRestantes = 0;

        $date1 = date_create($controlo->controle->final ?? NULL);
        $date2 = date_create($dataFinal ?? NULL);


        // if (!$date1) {
        //     return 0;
        // }

        // $diff = date_diff($date1, $date2);

        // // Se for negativo retorna 0
        // if ($diff->invert == 1) {
        //     return 0;
        // }

        // return (int) $diff->format("%a");

        // $date2 = date_create($controlo->inicio);
        $diff = date_diff($date1, $date2);
        $diasRestantes = $diff->format("%a");


        return $diasRestantes;
    }

    public function tem_perfil(string $string)
    {
        // Converte os módulos para uma coleção se não forem uma
        $modulos = collect($this->empresa_modulos);

        // Verifica se o nome do módulo existe na coleção de módulos
        return $modulos->contains(function ($modulo) use ($string) {
            return $modulo->modulo === $string; // Supondo que o módulo tenha uma propriedade 'nome'
        });

        // Verifica se a string está presente no array
        // return in_array($string, $modulos);
    }

    public function tem_permissao(string $string)
    {
        // Converte os módulos para uma coleção se não forem uma
        $modulos = collect($this->tipo_entidade->modulos);

        // Verifica se o nome do módulo existe na coleção de módulos
        return $modulos->contains(function ($modulo) use ($string) {
            return $modulo->modulo === $string; // Supondo que o módulo tenha uma propriedade 'nome'
        });

        // Verifica se a string está presente no array
        // return in_array($string, $modulos);
    }
}
