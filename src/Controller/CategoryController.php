<?php

declare(strict_types=1);

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
        $this->forLogged();
    }

    # Method create category => ONLY POST => Form in layout (main) in sidebar
    public function createAction()
    {
        if ($name = $this->request->isPost(['name'])) {

            $this->model->set([
                'name' => $name,
                'user' => $this->user->getId(),
            ]);

            if ($this->model->create()) {
                Session::success("Grupa została dodana");
            }
        }

        $this->redirectToLastPage();
    }


    # Method edit group name => ONLY POST => Form in category.manage
    public function editAction()
    {
        if ($name = $this->request->isPost(['name'])) {
            $category = $this->category();
            $category->update(['name' => $name]);
        }

        return $this->redirect('category.list');
    }

    # Here we can edit and delete categories || go in category to adds subs to group
    public function listAction()
    {
        View::set("Panel zarządzania grupami", 'category/list');
        return $this->render('category/list');
    }

    # Method deletes category with channels
    public function deleteAction()
    {
        if ($id = $this->request->isPost(['id'])) {
            $category = $this->category($id);
            $category->delete();
        }

        return $this->redirect("category.list");
    }

    // ===== // 

    # Method returns category by id
    private function category($id = null)
    {
        $id = $id ?? $this->request->getParam('id');

        if (($category = $this->user->_getCategory((int) $id, true)) == null) {
            Session::error("Podana grupa nie istnieje");
            $this->redirect("home", [], true);
        }

        return $category;
    }
}