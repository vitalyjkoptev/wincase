<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * CRM pages: leads, clients, cases, tasks, calendar, documents
     */
    public function crm(string $page)
    {
        return $this->renderPage('crm', $page);
    }

    /**
     * Finance pages: pos, invoices, payments, expenses, accounting
     */
    public function finance(string $page)
    {
        return $this->renderPage('finance', $page);
    }

    /**
     * Marketing pages: advertising, seo, social-media, brand, landing-pages
     */
    public function marketing(string $page)
    {
        return $this->renderPage('marketing', $page);
    }

    /**
     * Content pages: articles, create-article, categories
     */
    public function content(string $page)
    {
        return $this->renderPage('content', $page);
    }

    /**
     * Analytics pages: sales, traffic, performance
     */
    public function analytics(string $page)
    {
        return $this->renderPage('analytics', $page);
    }

    /**
     * Admin pages: users, roles, audit-log, system, settings
     */
    public function admin(string $page)
    {
        return $this->renderPage('admin', $page);
    }

    /**
     * Jobs pages: dashboard, listings, applications, candidates, employers, categories
     */
    public function jobs(string $page)
    {
        return $this->renderPage('jobs', $page);
    }

    /**
     * Auth pages: signin, signup, reset-password, etc.
     */
    public function auth(string $page)
    {
        $view = "auth.{$page}";
        if (view()->exists($view)) {
            return view($view);
        }
        abort(404);
    }

    /**
     * Client Portal pages: login, register, dashboard, etc.
     */
    public function client(string $page)
    {
        $view = "client.{$page}";
        if (view()->exists($view)) {
            return view($view);
        }
        abort(404);
    }

    /**
     * Staff Portal pages: dashboard, clients, cases, tasks, etc.
     */
    public function staff(string $page)
    {
        $view = "employee.{$page}";
        if (view()->exists($view)) {
            return view($view);
        }
        abort(404);
    }

    /**
     * Render a page from a section folder.
     */
    private function renderPage(string $section, string $page)
    {
        $view = "{$section}.{$page}";
        if (view()->exists($view)) {
            return view($view);
        }
        abort(404);
    }
}
