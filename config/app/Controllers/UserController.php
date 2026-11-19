<?php

class UserController extends Controller
{
    public function index(): void
    {
        Auth::requireSuperAdmin();
        $users = User::all();
        $this->view('users.index', ['title' => 'Manage Users', 'users' => $users]);
    }

    public function create(): void
    {
        Auth::requireSuperAdmin();
        $this->view('users.form', ['title' => 'Add User']);
    }

    public function store(): void
    {
        Auth::requireSuperAdmin();

        $name     = trim($this->input('name', ''));
        $email    = trim($this->input('email', ''));
        $password = $this->input('password', '');
        $role     = $this->input('role', 'cashier');

        $validRoles = ['super_admin', 'admin', 'manager', 'stock_keeper', 'cashier'];

        if (empty($name) || empty($email) || empty($password) || !in_array($role, $validRoles)) {
            $this->view('users.form', ['title' => 'Add User', 'error' => 'All fields are required.']);
            return;
        }

        User::create(['name' => $name, 'email' => $email, 'password' => $password, 'role' => $role]);

        ActivityLog::record(Auth::id(), 'user_created', 'user', null, "Created user: {$email}");

        Session::flash('success', 'User created successfully.');
        $this->redirect('/users');
    }

    public function toggle(int $id): void
    {
        Auth::requireSuperAdmin();
        User::toggleActive($id);
        $this->redirect('/users');
    }
}