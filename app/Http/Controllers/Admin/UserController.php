<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Storage;

class UserController extends Controller
{
    public function index()
    {
        $listUser = User::all();
        // dd($listUser);
        return view('admin.user.index', compact('listUser'));
    }
    public function create()
    {
        return view('admin.user.create');
    }
    public function store(Request $request)
    {
        // dd($request);
        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'birthday' => 'nullable|date',
            'gender' => 'required|exists:users,gender',
            'role' => 'required|exists:users,role',
        ]);
        $data['password'] = bcrypt($data['password']);
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            $data['avatar'] = null;
        }
        User::create($data);

        return redirect()->route('user.index')->with('success', 'User created successfully.');

    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        // dd($user);
        return view('admin.user.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        // dd($user);
        return view('admin.user.edit', compact('user'));
    }


    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'role' => 'required|exists:users,role',
            'status' => 'required|exists:users,status',
        ]);
        $user->update($data);
        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}
