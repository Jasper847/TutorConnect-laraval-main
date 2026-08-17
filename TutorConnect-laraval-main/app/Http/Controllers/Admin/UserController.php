<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['tutorProfile', 'studentProfile']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term)
                  ->orWhere('city', 'like', $term);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot deactivate super admin accounts.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $msg = $user->is_active ? 'User account activated.' : 'User account deactivated.';
        return back()->with('success', $msg);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete super admin accounts.');
        }

        $user->delete();
        return back()->with('success', 'User removed completely.');
    }
}
