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

    # Method shows all subscriptions and subscriptions for current category
    public function listAction()
    {
        View::set("Moje grupy", 'category/list');

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

    # Method create category => ONLY POST => Form in layout (main) in sidebar
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

    # Method edit group name => ONLY POST => Form in category.manage
    public function editAction()
    {
        if ($name = $this->request->isPost(['name'])) {
            $category = $this->search();
            $category->set('name', $name);
            $category->update(['name']);
        }

        return $this->redirect('category.manage');
    }

    # Here we can edit and delete categories || go in category to adds subs to group
    public function manageAction()
    {
        View::set("Panel zarządzania grupami", 'category/manage');
        return $this->render('category/manage');
    }

    # Method shows videos from single group
    public function showAction()
    {
        View::set("Filmy", 'category/show');

        $flow = $this->request->getParam('flow', 'grid');

        if ($flow != "grid") {
            $flow = "list";
        }

        $youtube = $this->google->getYoutubeService();
        $category = $this->search();
        $category->loadChannels();
        $videos = $youtube->listVideos($category->getChannels());

        return $this->render('category/show/' . $flow, [
            'category' => $category,
            'videos' => $this->sortVideoByDate($videos),

            'baseVideoUrl' => "https://www.youtube.com/watch?v=",
        ]);
    }

    # Method deletes category with channels
    public function deleteAction()
    {
        if ($id = $this->request->isPost(['id'])) {
            $category = $this->search($id);
            $category->delete();
        }

        return $this->redirect("category.manage");
    }

    # Method find category by ID
    private function search($id = null)
    {
        $id = $id ?? $this->request->getParam('id');
        $categories = $this->user->getCategories();
        $index = array_search($id, array_column($categories, 'id'));

        if ($index === false) {
            Session::error("Podana grupa nie istnieje");
            $this->redirect("home", [], true);
        }

        return $categories[$index];
    }

    # Method returns all subscriptions for logged in user
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

    # Method change local class channel to YouTube class channel and returns it
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

    # Method removes from all subscriptions, subscription which are in our category
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

    # Method sorts channels
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

    # Method sorts videos by date
    private function sortVideoByDate(array $videos = [])
    {
        for ($i = 0; $i < count($videos); $i++) {
            for ($j = 0; $j < count($videos) - 1 - $i; $j++) {
                $current = $videos[$j]->snippet->publishTime;
                $next = $videos[$j + 1]->snippet->publishTime;

                if ($current < $next) {
                    list($videos[$j], $videos[$j + 1]) = array($videos[$j + 1], $videos[$j]);
                }
            }
        }

        return $videos;
    }
}
