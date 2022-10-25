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

        return $this->redirect('home');
    }

    public function editAction()
    {
        // TODO
    }

    public function showAction()
    {
        $category = $this->search();
        return $this->render('category/show', ['category' => $category]);
    }

    public function deleteAction()
    {
        $category = $this->search();
        $category->delete();
        return $this->redirect("home");
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
