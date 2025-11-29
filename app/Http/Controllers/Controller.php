<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Resolve view by role namespace if available.
     * Example: calling $this->view('facilities.index') will first try
     * 'admin.facilities.index' / 'guru.facilities.index' / 'siswa.facilities.index'
     * according to the authenticated user's role. If not found, falls back to the provided view.
     *
     * @return \Illuminate\View\View
     */
    protected function view(string $view, array $data = [], array $mergeData = [])
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if (in_array($role, ['admin', 'guru', 'siswa'])) {
                $candidate = $role.'.'.$view;
                if (view()->exists($candidate)) {
                    return view($candidate, $data, $mergeData);
                }
            }
        }

        return view($view, $data, $mergeData);
    }
}
