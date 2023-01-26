<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Category;
use Phantom\Controller\AbstractController;
use Phantom\Helper\Request;
use Phantom\Helper\Session;
use Phantom\View;

class VideoController extends AbstractController
{
   private $youtube;
   public function __construct(Request $request)
   {
      parent::__construct($request);
      $this->model = new Category();
      $this->youtube = $this->google->getYoutubeService();
      $this->forLogged();
   }

   # Method shows videos from single group
   public function index(): View
   {
      $flow = $this->request->getParam('flow', 'grid') == "grid" ? "grid" : "list";
      $id = $this->request->getParam('id');

      if (($category = $this->user->_getCategory((int) $id, true)) == null) {
         Session::error("Podana grupa nie istnieje");
         $this->redirect("home", [], true);
      }

      View::set($category->getName(), 'video/index');
      $videos = $this->youtube->listVideos($category->_getChannels());

      return $this->render('video/' . $flow, [
         'category' => $category,
         'videos' => $this->sortVideoByDate($videos),

         'baseVideoUrl' => "https://www.youtube.com/watch?v=",
      ]);
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