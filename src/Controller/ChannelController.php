<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Category;
use App\Model\Channel;
use Phantom\Controller\AbstractController;
use Phantom\Helper\Request;
use Phantom\Helper\Session;
use Phantom\Repository\DBFinder;

class ChannelController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->model = new Channel();
        $this->forLogged();
    }

    // Method adds channel to category
    public function createAction()
    {
        if ($data = $this->request->isPost(['category_id', 'channelId'])) {
            $this->requireAccessToCategory($data['category_id']);

            $this->model->set([
                'category_id' => $data['category_id'],
                'channelId' => $data['channelId'],
            ]);

            if ($this->model->create()) {
                Session::success("Kanał został dodany");
            }
        }

        $this->redirectToLastPage("#notCategorized");
    }

    public function deleteAction()
    {
        if ($id = $this->request->isPost(['id'])) {
            $channel = $this->search($id);
            $this->requireAccessToCategory($channel->getCategoryId());
            $channel->delete();
            Session::success("Kanał został usunięty z grupy");
        }

        $this->redirectToLastPage();
    }

    private function search($id)
    {
        if ($channel = DBFinder::getInstance('channels')->findById($id, Channel::class)) {
            return $channel;
        }

        Session::error("Brak uprawnień");
        $this->redirect('home', [], true);
    }

    private function requireAccessToCategory($id)
    {
        $category = DBFinder::getInstance('categories')->find([
            'id' => $id,
            'user' => $this->user->getId()
        ], Category::class);

        if ($category == null) {
            Session::error("Brak uprawnień");
            $this->redirect('home', [], true);
        }
    }
}