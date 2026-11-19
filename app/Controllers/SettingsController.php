<?php

class SettingsController extends Controller
{
    public function index(): void
    {
        Auth::require();
        $this->view('settings.index', [
            'title' => 'Account Settings',
            'user'  => Auth::user()
        ]);
    }

    public function updateProfile(): void
    {
        Auth::require();
        $name  = trim($this->input('name', ''));
        $email = trim($this->input('email', ''));
        $userId = Auth::id();

        if (empty($name) || empty($email)) {
            Session::flash('error', 'Name and Email are required.');
            $this->redirect('/settings');
            return;
        }

        try {
            User::update($userId, [
                'name'  => $name,
                'email' => $email
            ]);
            
            // Update session data
            $user = Auth::user();
            $user['name']  = $name;
            $user['email'] = $email;
            Session::set('user', $user);

            ActivityLog::record($userId, 'profile_updated', 'user', $userId);
            Session::flash('success', 'Profile updated successfully.');
        } catch (Exception $e) {
            Session::flash('error', 'Failed to update profile. Email might be in use.');
        }

        $this->redirect('/settings');
    }

    public function updatePassword(): void
    {
        Auth::require();
        $current = $this->input('current_password', '');
        $new     = $this->input('new_password', '');
        $confirm = $this->input('confirm_password', '');
        $userId  = Auth::id();

        if (empty($current) || empty($new) || $new !== $confirm) {
            Session::flash('error', 'Please verify your password inputs.');
            $this->redirect('/settings');
            return;
        }

        // Verify current password
        $user = User::findById($userId);
        if (!$user || !password_verify($current, $user['password'])) {
            Session::flash('error', 'Current password is incorrect.');
            $this->redirect('/settings');
            return;
        }

        User::updatePassword($userId, $new);
        ActivityLog::record($userId, 'password_changed', 'user', $userId);
        
        Session::flash('success', 'Password changed successfully.');
        $this->redirect('/settings');
    }
}
