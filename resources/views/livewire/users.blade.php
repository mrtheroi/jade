<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900 dark:text-white">Users</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Lista de usuarios del sistema con su correo, estatus y rol.
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <flux:modal.trigger wire:model="open" name="store-user">
                <button
                    type="button"
                    class="block rounded-md bg-black px-3 py-2 text-center text-sm font-semibold text-white shadow-sm
                           hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                           focus-visible:outline-black-600 dark:bg-black-500 dark:hover:bg-black-400 dark:focus-visible:outline-black-500"
                >
                    Crear un nuevo usuario
                </button>
            </flux:modal.trigger>
        </div>
    </div>

    {{-- Buscador --}}
    <div class="mt-4">
        <flux:input wire:model.live="search" placeholder="Search..." icon="magnifying-glass" kbd="⌘K" />
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <table class="relative min-w-full divide-y divide-gray-300 dark:divide-white/15">
                    <thead>
                    <tr>
                        <th scope="col"
                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0 dark:text-white">
                            Nombre
                        </th>
                        <th scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                            Rol
                        </th>
                        <th scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                            Estatus
                        </th>
                        <th scope="col"
                            class="py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-0 dark:text-white">
                            Acciones
                        </th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                    @forelse($users as $user)
                        <tr class="group hover:bg-slate-50/70 dark:hover:bg-white/5">
                            {{-- Nombre + email (como en el ejemplo de Tailwind UI) --}}
                            <td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                <div class="flex items-center">
                                    {{-- Si no tienes avatar, puedes dejar solo el texto o poner iniciales --}}
                                    {{-- <div class="size-10 shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-500">
                                        {{ Str::of($user->name)->trim()->explode(' ')->map(fn($p) => Str::substr($p,0,1))->take(2)->implode('') }}
                                    </div> --}}
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                        </div>
                                        <div class="mt-1 text-gray-500 dark:text-gray-400">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Rol --}}
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500 dark:text-gray-400">
                                @if($user->hasRole('Super'))
                                    <span
                                        class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-600/20 dark:bg-purple-900/40 dark:text-purple-300 dark:ring-purple-500/60">
                                        Super
                                    </span>
                                @elseif($user->hasRole('Admin'))
                                    <span
                                        class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-900/40 dark:text-amber-300 dark:ring-amber-500/60">
                                        Admin
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-900/40 dark:text-blue-300 dark:ring-blue-500/60">
                                        Usuario
                                    </span>
                                @endif
                            </td>

                            {{-- Estatus --}}
                            <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500 dark:text-gray-400">
                                @if (is_null($user->deleted_at))
                                    <span
                                        class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/30 dark:text-green-400 dark:ring-green-500/50">
                                        Activo
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-400/20 dark:bg-gray-800/60 dark:text-gray-300 dark:ring-gray-500/60">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="whitespace-nowrap py-5 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                @if (is_null($user->deleted_at))
                                    <div class="flex justify-end gap-4 text-xs text-slate-400">
                                        {{-- Editar --}}
                                        <button
                                            type="button"
                                            wire:click.prevent="edit({{ $user->id }})"
                                            class="inline-flex items-center gap-1 text-amber-600 hover:text-amber-900
                                                   dark:text-amber-400 dark:hover:text-amber-300 transition"
                                        >
                                            <i class="fa-thin fa-pen text-[0.9rem]"></i>
                                            <span>Editar</span>
                                        </button>

                                        {{-- Eliminar --}}
                                        <button
                                            type="button"
                                            wire:click.prevent="deleteConfirmation({{ $user->id }})"
                                            class="inline-flex items-center gap-1 text-red-600 hover:text-red-800
                                                   dark:text-red-400 dark:hover:text-red-300 transition"
                                        >
                                            <i class="fa-thin fa-trash text-[0.9rem]"></i>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                No existen registros que mostrar.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($users->hasPages())
        <div class="p-2 flex justify-end mr-6">
            {{ $users->links('livewire::tailwind') }}
        </div>
    @endif

    @include('livewire.modals.form-user')
    @livewire('confirm-modal')
</div>
