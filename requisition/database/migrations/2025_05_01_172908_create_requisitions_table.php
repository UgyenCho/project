<?php

// database/migrations/xxxx_xx_xx_create_requisitions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('department_id');
            $table->date('requisition_date');
            $table->string('requester_name');
            $table->string('requester_designation');

            $table->enum('status', [
                'Pending',
                'Cancelled by LRC',
                'Waiting for HOD approval',
                'Rejected by HOD',
                'Waiting for Finance approval',
                'Rejected by Finance',
                'Waiting for President approval',
                'Rejected by President',
                'Waiting for Store Manager action',
                'Approved'
            ])->default('Pending');

            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
