<?php
namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MultipleAuthController extends Controller
{
// Create new user
    public function createMultipleregister(Request $request)
    {
        // dd($request->all([]));
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:superadmin,admin,manager,supervisor,employee,student,parents,agent'
        ]);
        
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);
        $user->markEmailAsVerified();

        return response()->json(['status' => 'success', 'message' => 'User created successfully']);
    }

    // Update user
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:superadmin,admin,manager,supervisor,employee,student,parents,agent',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->syncRoles([$request->role]);

        return response()->json(['status' => 'success', 'message' => 'User updated successfully']);
    }

}
?>