<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'workspace_name' => 'Test Workspace',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertDatabaseHas('tenants', ['name' => 'Test Workspace']);
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => 'user',
        'tenant_role' => 'owner',
    ]);
});
