<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServidorEfetivo extends Model
{
    use HasFactory;

    protected $table = 'servidor_efetivo';

    protected $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'pes_id',
        'se_matricula',
    ];

    /**
     * Relacionamento com a tabela pessoa
     */
    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class, 'pes_id', 'pes_id');
    }

    /**
     * Relacionamento com lotação
     */
    public function lotacaoAtiva(): HasOne
    {
        return $this->hasOne(Lotacao::class, 'pes_id', 'pes_id')
            ->whereNull('lot_data_remocao'); // Considerando que remoção NULL significa ativo
    }

    /**
     * Relacionamento com todas as lotações
     */
    public function lotacoes(): HasMany
    {
        return $this->hasMany(Lotacao::class, 'pes_id', 'pes_id');
    }

    /**
     * Relacionamento com foto (através da tabela pessoa)
     */
    public function foto(): HasOne
    {
        return $this->hasOne(FotoPessoa::class, 'pes_id', 'pes_id')
            ->latest('fp_data'); // Pega a foto mais recente
    }
}
