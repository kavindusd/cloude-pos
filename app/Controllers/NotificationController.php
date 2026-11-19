<?php

class NotificationController extends Controller
{
    public function index(): void
    {
        Auth::require();
        $this->view('notifications.index', ['title' => 'Notifications']);
    }
}
