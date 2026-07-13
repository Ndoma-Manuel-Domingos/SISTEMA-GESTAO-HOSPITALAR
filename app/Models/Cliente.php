<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DateTime;

class Cliente extends Model
{

    use HasFactory;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nif',
        'nome',
        'pais',
        'conta',
        'code',
        'tipo_cliente',
        'parent_id',

        'nome_do_pai',
        'nome_da_mae',
        'data_nascimento',
        'genero',
        'estado_civil_id',
        'seguradora_id',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        'status',
        'vencimento',
        'gestor_conta',
        'morada',
        'codigo_postal',
        'localidade',
        'telefone',
        'telemovel',
        'email',
        'website',
        'referencia_externa',
        'responsavel_nome',
        'responsavel_contacto',
        'observacao',
        'user_id',
        'subconta_id',
        'entidade_id',
    ];

    public function plano()
    {
        return $this->hasOne(SeguradoraPlanoBeneficiador::class, 'beneficiario_id', 'id');
    }

    public function contasHospitalares()
    {
        return $this->hasMany(ContaHospitalar::class, 'paciente_id', 'id');
    }

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'cliente_id', 'id');
    }

    public function filhos()
    {
        return $this->hasMany(Cliente::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(Cliente::class, 'id', 'parent_id');
    }

    public function cartao()
    {
        return $this->hasOne(ContaCliente::class, 'cliente_id', 'id');
    }

    public function contratos()
    {
        return $this->hasMany(ClienteContrato::class, 'cliente_id', 'id');
    }

    public function internamentos()
    {
        return $this->hasMany(Internamento::class, 'paciente_id', 'id');
    }

    public function exames()
    {
        return $this->hasMany(Exame::class, 'paciente_id', 'id');
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'paciente_id', 'id');
    }

    public function estado_civil()
    {
        return $this->belongsTo(EstadoCivil::class, 'estado_civil_id', 'id');
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'cliente_id', 'id');
    }

    public function seguradora()
    {
        return $this->belongsTo(Seguradora::class, 'seguradora_id', 'id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'cliente_id', 'id');
    }


    public function idade($data)
    {
        $dataAtual = new DateTime();
        $dataNascimento = new DateTime($data);
        $diferenca = $dataNascimento->diff($dataAtual);
        return $diferenca->y;
    }
}
