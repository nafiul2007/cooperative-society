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
        Schema::create('society_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_no')->unique();
            $table->text('address');
            $table->string('phone');
            $table->string('email')->unique();
            $table->date('established_date')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null'); //created_by_user_id
            $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->onDelete('set null'); //updated_by_user_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('society_infos');
    }
};
