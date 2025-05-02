<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Requisition; // Import Requisition model

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisition_items', function (Blueprint $table) {
            $table->id();
            // Foreign key linking to the main requisition
            $table->foreignIdFor(Requisition::class)->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->text('item_description')->nullable();
            $table->string('item_quantity'); // Store as string initially, maybe cast later
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisition_items');
    }
};