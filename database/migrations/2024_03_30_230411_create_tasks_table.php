<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Task;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('status', Task::STATUS);
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->integer('duration')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->dropForeign('tasks_assigned_to_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
