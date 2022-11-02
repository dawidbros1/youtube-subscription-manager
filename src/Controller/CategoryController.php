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
        $this->forLogged();
    }

    public function listAction()
    {
        View::set("Moje grupy", 'list');

        $youtube = $this->google->getYoutubeService();
        $category = $this->search();
        $category->loadChannels();

        $channelsFromCategory = $this->getChannelsFromCategory($category, $youtube);
        $subscriptions = $this->getSubscriptions($youtube);

        if (!empty($channelsFromCategory)) {
            $subscriptions = $this->difference($subscriptions, $channelsFromCategory);
        }

        return $this->render("category/list", [
            'category' => $category,
            'channelsFromCategory' => $this->sortChannels($category, $channelsFromCategory),
            'subscriptions' => $subscriptions,
            'channels' => $category->getChannels(),
        ]);
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

    private function getSubscriptions($youtube)
    {
        $items = [];
        $subscriptions = $youtube->listSubscriptions();

        while ($subscriptions->nextPageToken != null) {
            $items = array_merge($items, $subscriptions->items);
            $pageToken = $subscriptions->nextPageToken;
            $subscriptions = $youtube->listSubscriptions($pageToken);
        }

        return array_merge($items, $subscriptions->items);
    }

    private function getChannelsFromCategory($category, $youtube)
    {
        $channelsFromCategory = $category->getChannels();
        $ids = array_column($channelsFromCategory, 'channelId');

        if (empty($ids)) {
            return [];
        }

        $channels = $youtube->getChannels($ids);

        return $channels;
    }

    private function difference($items, $channels)
    {
        $ids = array_column($channels->items, 'id');

        foreach ($items as $key => $item) {
            if (in_array($item->snippet->resourceId->channelId, $ids)) {
                unset($items[$key]);
            }
        }

        return $items;
    }

    private function sortChannels($category, $channels)
    {
        $channelsFromCategory = $category->getChannels();
        $ids = array_column($channelsFromCategory, 'channelId');
        $output = [];

        for ($i = 0; $i < count($channels->items); $i++) {
            foreach ($channels->items as $channel) {
                if ($channel->id == $ids[$i]) {
                    $output[] = $channel;
                }
            }
        }

        return $output;
    }
}
