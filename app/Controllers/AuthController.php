<?php

class AuthController extends Controller
{
    public function showLogin(): void
    {
        // Already logged in — go home
        if (Auth::check()) {
            $this->redirect(Auth::homeRoute());
        }
        $this->view('auth.login');
    }

    public function login(): void
    {
        $email    = trim($this->input('email', ''));
        $password = $this->input('password', '');

        if (empty($email) || empty($password)) {
            $this->view('auth.login', ['error' => 'Email and password are required.']);
            return;
        }

        $user = User::findByEmail($email);

        if (!$user || !User::verifyPassword($password, $user['password'])) {
            $this->view('auth.login', ['error' => 'Invalid email or password.']);
            return;
        }

        Auth::login($user);

        // Log the login activity
        ActivityLog::record($user['id'], 'login');

        $this->redirect(Auth::homeRoute());
    }

    public function logout(): void
    {
        if (Auth::check()) {
            ActivityLog::record(Auth::id(), 'logout');
        }

        Auth::logout();
        $this->redirect('/login');
    }
}