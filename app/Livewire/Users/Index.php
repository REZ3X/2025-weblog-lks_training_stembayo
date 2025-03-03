<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $userId;
    public $selectRole;
    public $search;
    public $rowPerPage = 10;

    public function render()
    {
        if (!auth()->user()->can('user manage')){
            abort(403, 'You do not have permission to manage users.');
    }
    return View('livewire.users.index', [
        'users' => $this->search === null ?
            User::paginate($this->rowPerPage) :
            User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->paginate($this->rowPerPage),
        'roles' => Role::all(),
    ]);
}

public function updateUserRole($userId, $roleId)
{
    try {
        if(!auth()->user()->can('user manage')){
            session()->flash('error', 'You do not have permission to manage users.');
            return;
        }

        $user = User::findOrFail($userId);
        $role = Role::findById($roleId);

        if(!$role) {
            session()->flash('error', 'Role not found.');
            return;
        }

        $user->syncRoles($role);
        session()->flash('message', "Role updated for {$user->name}.");
    } catch (\Exception $e) {
        session()->flash('error', $e->getMessage());
    }
}
}