<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('livres', function (Blueprint $table) {
            $table->integer('exemplaires_disponibles')->default(0)->after('nombre_exemplaires');
        });

        DB::table('livres')->update([
            'exemplaires_disponibles' => DB::raw('nombre_exemplaires'),
        ]);

        DB::table('livres')->where('exemplaires_disponibles', '<=', 0)->update([
            'disponible' => false,
        ]);

        DB::table('livres')->where('exemplaires_disponibles', '>', 0)->update([
            'disponible' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livres', function (Blueprint $table) {
            $table->dropColumn('exemplaires_disponibles');
        });
    }
};
