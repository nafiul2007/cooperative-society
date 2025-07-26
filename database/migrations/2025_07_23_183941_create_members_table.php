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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->notNullable();
            $table->string('mobile_number')->notNullable();
            $table->string('nid')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('tin')->nullable();
            $table->decimal('business_share', 18, 3)->nullable();
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
        Schema::dropIfExists('members');
    }
};
