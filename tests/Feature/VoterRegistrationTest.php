<?php

use App\Models\User;

describe('Voter Registration', function () {
    test('voter registration page is accessible', function () {
        $response = $this->get(route('register'));

        $response->assertOk();
        $response->assertViewIs('auth.register');
    });

    test('voter can register with valid data', function () {
        $response = $this->post(route('register.post'), [
            'name' => 'John Doe',
            'student_id' => 'JDO123-0001/2024',
            'email' => 'john@university.ac.ke',
            'phone' => '+254712345678',
            'faculty' => 'Computing & Information Technology',
            'department' => 'Computer Science',
            'course' => 'BSc Computer Science',
            'year_of_study' => 2,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $response->assertRedirect(route('voter.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'john@university.ac.ke')->first();
        expect($user)->not->toBeNull();
        expect($user->role)->toBe('voter');
        expect($user->name)->toBe('John Doe');
        expect($user->student_id)->toBe('JDO123-0001/2024');
    });

    test('voter registration requires name', function () {
        $response = $this->post(route('register.post'), [
            'student_id' => 'JDO123-0001/2024',
            'email' => 'john@university.ac.ke',
            'phone' => '+254712345678',
            'faculty' => 'Computing & Information Technology',
            'department' => 'Computer Science',
            'course' => 'BSc Computer Science',
            'year_of_study' => 2,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertGuest();
    });

    test('voter registration requires unique student id', function () {
        User::factory()->voter()->create(['student_id' => 'JDO123-0001/2024']);

        $response = $this->post(route('register.post'), [
            'name' => 'John Doe',
            'student_id' => 'JDO123-0001/2024',
            'email' => 'john@university.ac.ke',
            'phone' => '+254712345678',
            'faculty' => 'Computing & Information Technology',
            'department' => 'Computer Science',
            'course' => 'BSc Computer Science',
            'year_of_study' => 2,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors('student_id');
    });

    test('voter registration requires unique email', function () {
        User::factory()->voter()->create(['email' => 'john@university.ac.ke']);

        $response = $this->post(route('register.post'), [
            'name' => 'John Doe',
            'student_id' => 'JDO123-0001/2024',
            'email' => 'john@university.ac.ke',
            'phone' => '+254712345678',
            'faculty' => 'Computing & Information Technology',
            'department' => 'Computer Science',
            'course' => 'BSc Computer Science',
            'year_of_study' => 2,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors('email');
    });

    test('voter registration requires terms acceptance', function () {
        $response = $this->post(route('register.post'), [
            'name' => 'John Doe',
            'student_id' => 'JDO123-0001/2024',
            'email' => 'john@university.ac.ke',
            'phone' => '+254712345678',
            'faculty' => 'Computing & Information Technology',
            'department' => 'Computer Science',
            'course' => 'BSc Computer Science',
            'year_of_study' => 2,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('terms');
    });

    test('voter registration requires strong password', function () {
        $response = $this->post(route('register.post'), [
            'name' => 'John Doe',
            'student_id' => 'JDO123-0001/2024',
            'email' => 'john@university.ac.ke',
            'phone' => '+254712345678',
            'faculty' => 'Computing & Information Technology',
            'department' => 'Computer Science',
            'course' => 'BSc Computer Science',
            'year_of_study' => 2,
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors('password');
    });
});
