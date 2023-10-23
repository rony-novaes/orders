<?php

use App\Models\Address;
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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('address_type');
            $table->bigInteger('at_id');
            $table->tinyInteger('at_type');
            $table->string('public_place', 355);
            $table->string('number', 55);
            $table->string('complement', 255)->nullable();
            $table->string('zip_code', 15);
            $table->string('district', 355)->nullable();
            $table->string('city', 50);
            $table->string('state', 50);
            $table->string('country', 50)->default(Address::COUNTRY_BR);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
