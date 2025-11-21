<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('npi_records', function (Blueprint $table) {
            $table->boolean('is_called')->default(false)->after('taxonomy_desc');
            $table->integer('call_count')->default(0)->after('is_called');
            $table->timestamp('last_called_at')->nullable()->after('call_count');
            $table->string('called_by')->nullable()->after('last_called_at');
            $table->text('call_notes')->nullable()->after('called_by');
        });
    }

    public function down(): void
    {
        Schema::table('npi_records', function (Blueprint $table) {
            $table->dropColumn([
                'is_called',
                'call_count',
                'last_called_at',
                'called_by',
                'call_notes'
            ]);
        });
    }
};
