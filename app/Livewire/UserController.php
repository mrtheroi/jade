<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
class UserController extends Component
{
    use withPagination, WithoutUrlPagination;

    #[Url]
    public $search = '';
    public $open = false;
    public $sort = 'id', $direction = 'DESC';

    #[Rule('required|min:6')]
    public $name;
    #[Rule('required|email')]
    public $email;
    #[Rule('required|string|min:8')]
    public $password;
    public $user_id;
    public $selected_id;
    public $rol = [];

    public function updatingOpen(): void
    {
        if(!$this->open) {
            return;
        }
        $this->resetUI();
    }

    public function resetUI(): void
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->open = false;
    }

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
    }
    public function render()
    {
        $query = User::query()->withTrashed();
        $query = $this->applySearch($query);

        $users = $query->paginate(10);

        $roles = Role::all();

        return view('livewire.users', compact('users', 'roles'));
    }

    public function save(): void
    {
        $this->validate();
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        $user->syncRoles($this->rol);

        $this->resetUI();
        $this->dispatch('notify', message: 'El usuario se creo con éxito', type: 'success');
    }

    public function edit(User $user)
    {
        $user = User::findOrFail($user->id);
        $this->selected_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->rol = $user->roles->pluck('name')->toArray();
        $this->open = true;
    }

    public function update()
    {
        $this->validate();
        $user = User::findOrFail($this->selected_id);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);
        $user->syncRoles($this->rol);

        $this->resetUI();
        $this->dispatch('notify', message: 'El usuario se actualizo con éxito', type: 'success');
    }

    public function deleteConfirmation($id): void
    {
        $this->dispatch('showConfirmationModal', userId: $id)->to(ConfirmModal::class);

    }

    #[On('deleteConfirmed')]
    public function destroy($id): void
    {
        $user = User::where('id', $id)->first();
        $user->delete();
        $this->dispatch('notify', message: 'El usuario se elimino con éxito', type: 'success');
    }
}
