<?php

use App\Models\AuditLog;
use App\Models\User;

describe('AuditLog Policy', function () {
    test('admin can view audit logs', function () {
        $admin = User::factory()->admin()->create();

        expect($admin->can('viewAny', AuditLog::class))->toBeTrue();
    });

    test('voter cannot view audit logs', function () {
        $voter = User::factory()->voter()->create();

        expect($voter->can('viewAny', AuditLog::class))->toBeFalse();
    });

    test('candidate cannot view audit logs', function () {
        $candidate = User::factory()->candidate()->create();

        expect($candidate->can('viewAny', AuditLog::class))->toBeFalse();
    });

    test('admin can view specific audit log', function () {
        $admin = User::factory()->admin()->create();
        $auditLog = AuditLog::factory()->create();

        expect($admin->can('view', $auditLog))->toBeTrue();
    });

    test('voter cannot view specific audit log', function () {
        $voter = User::factory()->voter()->create();
        $auditLog = AuditLog::factory()->create();

        expect($voter->can('view', $auditLog))->toBeFalse();
    });
});
