<?php

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
        /*
            should probably have some expiration functionality here but
            to keep it simple I'm assuming key rotation when exposed instead.
            this of course opens up risks as the key never expires.

            If I have time to write a login function then I will come back and work this in.
        */
        Schema::create('access_tokens', function (Blueprint $table) {
            $table->id();
            $table->integer('app_id');
            $table->string('access_token')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_tokens');
    }
};
