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
        Schema::table('event_participations', function (Blueprint $table) {
            $table->json('participant_names')->nullable()->after('status_reason');
            $table->json('participant_emails')->nullable()->after('participant_names');
            $table->integer('number_of_participants')->default(1)->after('participant_emails');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_participations', function (Blueprint $table) {
            $table->dropColumn(['participant_names', 'participant_emails', 'number_of_participants']);
        });
    }
};
