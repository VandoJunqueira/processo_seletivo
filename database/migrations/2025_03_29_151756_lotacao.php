<?php

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
        Schema::create('lotacao', function (Blueprint $table) {
            $table->id('lot_id');
            $table->foreignIdFor(Pessoa::class, 'pes_id')->constrained();
            $table->foreignIdFor(Unidade::class, 'unid_id')->constrained();
            $table->date('lot_data_lotacao');
            $table->date('lot_data_remocao');
            $table->string('lot_portaria', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotacao');
    }
};
