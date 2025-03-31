<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoPessoa extends Model
{
    use HasFactory;

    protected $table = 'foto_pessoa';

    protected $primaryKey = 'fp_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'pes_id',
        'ft_data',
        'fp_bucket',
        'fp_hash',
    ];
}
