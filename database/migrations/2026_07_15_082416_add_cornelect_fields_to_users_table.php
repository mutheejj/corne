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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'voter', 'candidate'])->default('voter')->after('email');
            $table->string('student_id')->unique()->nullable()->after('role');
            $table->string('phone')->nullable()->after('student_id');
            $table->string('faculty')->nullable()->after('phone');
            $table->string('department')->nullable()->after('faculty');
            $table->string('course')->nullable()->after('department');
            $table->integer('year_of_study')->nullable()->after('course');
            $table->string('avatar')->nullable()->after('year_of_study');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('two_factor_secret')->nullable()->after('last_login_at');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'student_id', 'phone', 'faculty', 'department',
                'course', 'year_of_study', 'avatar', 'is_active',
                'last_login_at', 'two_factor_secret', 'two_factor_confirmed_at',
            ]);
        });
    }
};
