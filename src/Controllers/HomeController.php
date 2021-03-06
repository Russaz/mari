<?php

namespace Featherwebs\Mari\Controllers;

use Featherwebs\Mari\Models\Page;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;

class HomeController extends BaseController
{
    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = Page::find(fw_setting('homepage'));

        if ($page) {
            $view = 'pages.' . $page->view;
            if (view()->exists($view)) {
                return view($view, compact('page'));
            }
        }

        return abort(404, 'Invalid View');
    }

    public function page($slug)
    {
        $page = Page::whereSlug($slug)->published()->first();

        $view = 'default';
        if ( ! $page) {
            abort(404);
        } else {
            if ( ! empty($page->view) && view()->exists('pages.' . $page->view)) {
                $view = $page->view;
            }
        }

        return view('pages.' . $view, compact('page'));
    }
}
