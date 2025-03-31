<?php

use App\Models\Pessoa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('foto_pessoa', function (Blueprint $table) {
            $table->id('fp_id');
            $table->foreignIdFor(Pessoa::class, 'pes_id')->constrained();
            $table->date('ft_data');
            $table->string('fp_bucket', 50);
            $table->string('fp_hash', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_pessoa');
    }
};
