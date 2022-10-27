<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Model\Category;
use Phantom\Controller\AbstractController;
use Phantom\Helper\Request;
use Phantom\Helper\Session;
use Phantom\View;

class CategoryController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->model = new Category();
    }

    public function listAction()
    {
        // TODO
        View::set("Moje grupy", 'category');
        return $this->render("category/list", ['name' => null]);
    }

    // Method create category => ONLY POST => Form in layout (main) in sidebar
    public function createAction()
    {
        if ($name = $this->request->isPost(['name'])) {
            $this->model->setArray([
                'name' => $name,
                'user' => $this->user->getId(),
            ]);

            if ($this->model->create()) {
                Session::success("Grupa została dodana");
            }
        }

        $this->redirectToLastPage();
    }

    // Method edit group name => ONLY POST => Form in category.manage
    public function editAction()
    {
        if ($name = $this->request->isPost(['name'])) {
            $category = $this->search();
            $category->set('name', $name);
            $category->update(['name']);
        }

        return $this->redirect('category.manage');
    }

    // Here we can edit and delete categories || go in category to adds subs to group
    public function manageAction()
    {
        View::set("Panel zarządzania grupami", 'manage');
        // List all subs $user->getCategories()
        // Add icon to delete | hidden form to edit

        return $this->render('category/manage');
    }

    // Method shows videos from single group
    public function showAction()
    {
        $category = $this->search();
        return $this->render('category/show', ['category' => $category]);
    }

    public function deleteAction()
    {
        if ($this->request->isPost()) {
            $category = $this->search();
            $category->delete();
        }

        return $this->redirect("category.manage");
    }

    private function search()
    {
        $id = $this->request->getParam('id');
        $categories = $this->user->getCategories();
        $index = array_search($id, array_column($categories, 'id'));

        if ($index === false) {
            Session::error("Podana grupa nie istnieje");
            $this->redirect("home", [], true);
        }

        return $categories[$index];
    }
}
