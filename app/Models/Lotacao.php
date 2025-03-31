<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lotacao extends Model
{
    use HasFactory;

    protected $table = 'lotacao';

    protected $primaryKey = 'lot_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'pes_id',
        'unid_id',
        'lot_data_lotacao',
        'lot_data_remocao',
        'lot_portaria',
    ];

    /**
     * Relacionamento com servidor efetivo (se existir)
     */
    public function servidorEfetivo(): HasOne
    {
        return $this->hasOne(ServidorEfetivo::class, 'pes_id', 'pes_id');
    }

    public function unidade(): HasOne
    {
        return $this->hasOne(Unidade::class, 'unid_id', 'unid_id');
    }
}
