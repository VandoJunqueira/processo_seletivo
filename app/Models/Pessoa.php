<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pessoa extends Model
{
    use HasFactory;

    protected $table = 'pessoa';

    protected $primaryKey = 'pes_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'pes_nome',
        'pes_data_nascimento',
        'pes_sexo',
        'pes_mae',
        'pes_pai',
    ];


    /**
     * Relacionamento com endereço
     */
    public function endereco(): BelongsToMany
    {
        return $this->belongsToMany(
            Endereco::class,
            'pessoa_endereco',
            'pes_id',
            'end_id'
        );
    }


    protected function getIdadeAttribute(): ?int
    {
        return $this->pes_data_nascimento
            ? Carbon::parse($this->pes_data_nascimento)->age
            : null;
    }

    /**
     * Relacionamento com servidor efetivo (se existir)
     */
    public function servidorEfetivo(): HasOne
    {
        return $this->hasOne(ServidorEfetivo::class, 'pes_id', 'pes_id');
    }

    /**
     * Relacionamento com servidor temporário (se existir)
     */
    public function servidorTemporario(): HasOne
    {
        return $this->hasOne(ServidorTemporario::class, 'pes_id', 'pes_id');
    }

    /**
     * Relacionamento com foto da pessoa
     */
    public function foto(): HasOne
    {
        return $this->hasOne(FotoPessoa::class, 'pes_id', 'pes_id')
            ->latest('fp_data');
    }

    /**
     * Relacionamento com todas as lotações
     */
    public function lotacoes(): HasMany
    {
        return $this->hasMany(Lotacao::class, 'pes_id', 'pes_id');
    }

    /**
     * Relacionamento com lotação ativa
     */
    public function lotacaoAtiva(): HasOne
    {
        return $this->hasOne(Lotacao::class, 'pes_id', 'pes_id')
            ->whereNull('lot_data_remocao');
    }
}
