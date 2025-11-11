<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('npi_records', function (Blueprint $table) {
            $table->id();
            $table->string('npi_number')->unique();
            $table->string('organization_name')->nullable();
            $table->string('authorized_official')->nullable();
            $table->string('official_title')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('taxonomy_desc')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('npi_records');
    }
};
    