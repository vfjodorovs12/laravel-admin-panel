<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('flag')->nullable(); // emoji или url
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('languages');
    }
};
