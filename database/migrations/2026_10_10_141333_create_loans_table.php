
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                ->constrained('items')
                ->restrictOnDelete();

            $table->string('borrower_name');
            $table->unsignedInteger('quantity');
            $table->enum('status', [
                'DIPINJAM',
                'DIKEMBALIKAN',
            ])->default('DIPINJAM');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
