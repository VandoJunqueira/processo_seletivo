<?php

use App\Models\Cidade;
use App\Models\Endereco;
use App\Models\Pessoa;
use App\Models\Unidade;
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
        Schema::create('pessoa_endereco', function (Blueprint $table) {
            $table->foreignIdFor(Pessoa::class, 'pes_id')->constrained();
            $table->foreignIdFor(Endereco::class, 'end_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoa_endereco');
    }
};
