<?php

namespace App\Http\Controllers\Admin\User;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = User::with('credit');

        if ($request->has('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        $title = 'จัดการผู้ใช้';
        return view('admins.user-manage', compact('users','title'));
    }
}
