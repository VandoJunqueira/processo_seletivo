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
        Schema::create('servidor_temporario', function (Blueprint $table) {
            $table->foreignIdFor(Pessoa::class, 'pes_id')->constrained();
            $table->date('st_data_admissao');
            $table->date('st_data_demissao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servidor_temporario');
    }
};
