<?php

use App\Models\User;
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
        Schema::create('urls', function (Blueprint $table) {
            $table->id();
            $table->string('original_url');
            $table->string('code')->unique()->index();
            $table->string('base_url')->nullable();
            $table->integer('visits')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('api_generated')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
