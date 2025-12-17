<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryController extends Component
{
    use WithPagination;

    // Buscador sincronizado con la URL
    #[Url]
    public $search = '';

    // Control del modal
    public bool $open = false;

    // Id de la categoría actual (para editar)
    public ?int $categoryId = null;

    // Campos del formulario
    #[Rule('required|string|max:150')]
    public string $business_unit = '';

    #[Rule('required|string|max:150')]
    public string $expense_name = '';

    #[Rule('required|string|max:150')]
    public string $provider_name = '';

    #[Rule('required|boolean')]
    public $is_active = 1;

    // Para confirmar eliminación
    public ?int $deleteId = null;

    // Al cambiar el buscador, regresamos a la página 1
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Abrir modal en modo "crear"
    public function create(): void
    {
        $this->resetForm();
        $this->open = true;
    }

    // Abrir modal en modo "editar"
    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->categoryId    = $category->id;
        $this->business_unit = $category->business_unit;
        $this->expense_name  = $category->expense_name;
        $this->provider_name = $category->provider_name;
        $this->is_active     = $category->is_active ? 1 : 0;

        $this->open = true;
    }

    // Cerrar modal y limpiar errores
    public function closeModal(): void
    {
        $this->open = false;
        $this->resetValidation();
    }

    // Guardar (create / update)
    public function save(): void
    {
        $validated = $this->validate();

        Category::updateOrCreate(['id' => $this->categoryId], $validated,);

        // Opcional: puedes lanzar aquí un toast Livewire/Alpine
         $this->dispatch('notify', message: 'Categoría guardada correctamente.',type: 'success');

        $this->closeModal();
        $this->resetForm();
    }

    // Preparar eliminación
    public function deleteConfirmation($id): void
    {
        $this->dispatch('showConfirmationModal', userId: $id)->to(ConfirmModal::class);

    }

    #[On('deleteConfirmed')]
    public function destroy($id): void
    {
        $category = Category::where('id', $id)->first();
        $category->delete();
        $this->dispatch('notify', message: 'La categoria se elimino con éxito', type: 'success');
    }

    // Resetear solo campos del formulario (no buscador ni paginación)
    protected function resetForm(): void
    {
        $this->categoryId    = null;
        $this->business_unit = '';
        $this->expense_name  = '';
        $this->provider_name = '';
        $this->is_active     = 1;
    }

    public function render()
    {
        $query = Category::query()
            ->orderBy('business_unit')
            ->orderBy('expense_name');

        if ($this->search) {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                $q->where('business_unit', 'like', "%{$search}%")
                    ->orWhere('expense_name', 'like', "%{$search}%")
                    ->orWhere('provider_name', 'like', "%{$search}%");
            });
        }

        return view('livewire.category-controller', [
            'categories' => $query->paginate(10),
        ]);
    }
}
