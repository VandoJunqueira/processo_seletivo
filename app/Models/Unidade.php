<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unidade extends Model
{
    use HasFactory;

    protected $table = 'unidade';

    protected $primaryKey = 'unid_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'unid_nome',
        'unid_sigla',
    ];

    /**
     * Relacionamento com endereço
     */
    public function endereco(): BelongsToMany
    {
        return $this->belongsToMany(
            Endereco::class,
            'unidade_endereco',
            'unid_id',
            'end_id'
        );
    }
}
