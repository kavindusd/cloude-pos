<?php

class CategoryController extends Controller
{
    public function index(): void
    {
        Auth::requirePermission('categories');
        $categories = Category::all();
        $this->view('categories.index', ['title' => 'Categories', 'categories' => $categories]);
    }

    public function store(): void
    {
        Auth::requirePermission('categories');

        $name        = trim($this->input('name', ''));
        $description = trim($this->input('description', ''));

        if (empty($name)) {
            Session::flash('error', 'Category name is required.');
            $this->redirect('/categories');
            return;
        }

        Category::create($name, $description);
        ActivityLog::record(Auth::id(), 'category_created', 'category', null, $name);
        Session::flash('success', "Category \"{$name}\" created.");
        $this->redirect('/categories');
    }

    public function edit(int $id): void
    {
        Auth::requirePermission('categories');
        $category = Category::findById($id);
        if (!$category) { Session::flash('error', 'Category not found.'); $this->redirect('/categories'); return; }
        $this->view('categories.index', ['title' => 'Categories', 'categories' => Category::all(), 'editing' => $category]);
    }

    public function update(int $id): void
    {
        Auth::requirePermission('categories');

        $name        = trim($this->input('name', ''));
        $description = trim($this->input('description', ''));

        if (empty($name)) {
            Session::flash('error', 'Category name is required.');
            $this->redirect('/categories');
            return;
        }

        Category::update($id, $name, $description);
        ActivityLog::record(Auth::id(), 'category_updated', 'category', $id, $name);
        Session::flash('success', "Category updated.");
        $this->redirect('/categories');
    }

    public function delete(int $id): void
    {
        Auth::requirePermission('categories');
        $category = Category::findById($id);
        if ($category) {
            Category::delete($id);
            ActivityLog::record(Auth::id(), 'category_deleted', 'category', $id, $category['name']);
            Session::flash('success', "Category \"{$category['name']}\" deleted.");
        }
        $this->redirect('/categories');
    }
}