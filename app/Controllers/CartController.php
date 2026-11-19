<?php

class CartController extends Controller
{
    public function index(): void
    {
        $this->view('dashboard.index');
    }
}
