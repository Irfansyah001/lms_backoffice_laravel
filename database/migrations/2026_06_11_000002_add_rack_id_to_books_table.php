<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('rack_id')->nullable()->after('category_id');
        });

        // Pindahkan data lokasi rak lama (string) menjadi baris di tabel racks,
        // lalu hubungkan setiap buku ke rak yang sesuai.
        $shelves = DB::table('books')
            ->whereNotNull('shelf_location')
            ->where('shelf_location', '!=', '')
            ->distinct()
            ->orderBy('shelf_location')
            ->pluck('shelf_location');

        foreach ($shelves as $shelf) {
            $rackId = DB::table('racks')->insertGetId([
                'name' => $shelf,
                'description' => 'Rak hasil migrasi data lama.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('books')
                ->where('shelf_location', $shelf)
                ->update(['rack_id' => $rackId]);
        }

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('shelf_location');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('shelf_location')->nullable()->after('category_id');
        });

        // Kembalikan nama rak ke kolom string pada buku.
        $racks = DB::table('racks')->pluck('name', 'id');

        foreach ($racks as $id => $name) {
            DB::table('books')
                ->where('rack_id', $id)
                ->update(['shelf_location' => $name]);
        }

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('rack_id');
        });
    }
};
