<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * DUPLICATE — Skipped.
     *
     * Permission tables are already created by the newer migration
     * 2026_05_21_071837_create_permission_tables.php which has already run.
     * This file is kept to avoid "migration not found" errors in batch tracking,
     * but does nothing when executed.
     */
    public function up(): void
    {
        // Tables already exist from 2026_05_21_071837_create_permission_tables.php
    }

    public function down(): void
    {
        // Nothing to undo — the newer migration handles this
    }
};
