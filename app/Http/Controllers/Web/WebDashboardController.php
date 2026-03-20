<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WebDashboardController extends Controller
{
    public function crm(string $page)
    {
        return $this->renderPage('crm', $page);
    }

    public function finance(string $page)
    {
        return $this->renderPage('finance', $page);
    }

    public function marketing(string $page)
    {
        return $this->renderPage('marketing', $page);
    }

    public function content(string $page)
    {
        return $this->renderPage('content', $page);
    }

    public function analytics(string $page)
    {
        return $this->renderPage('analytics', $page);
    }

    public function admin(string $page)
    {
        // Admin pages only for boss
        if (Auth::user()->role !== 'boss') {
            abort(403, 'Boss access required');
        }
        return $this->renderPage('admin', $page);
    }

    public function jobs(string $page)
    {
        return $this->renderPage('jobs', $page);
    }

    public function client(string $page)
    {
        $view = "client.{$page}";
        if (view()->exists($view)) {
            return view($view);
        }
        abort(404);
    }

    public function staff(string $page)
    {
        $view = "employee.{$page}";
        if (view()->exists($view)) {
            return view($view);
        }
        abort(404);
    }

    private function renderPage(string $section, string $page)
    {
        $view = "{$section}.{$page}";
        if (view()->exists($view)) {
            return view($view);
        }
        abort(404);
    }
}
