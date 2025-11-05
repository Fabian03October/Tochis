@extends('layouts.app')

@section('title', 'Categorías - Sistema POS')
@section('page-title', 'Gestión de Categorías')

@section('content')
<div class="fade-in">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Categorías</h1>
            <p class="text-gray-600">Gestiona las categorías de productos</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Nueva Categoría
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-tags text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Categorías</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $categories->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Categorías Activas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $categories->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Productos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $categories->sum(function($category) { return $category->products->count(); }) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Lista de Categorías</h3>
        </div>
        
        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoría
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Productos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Personalizable
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha de Creación
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center" 
                                             style="background-color: {{ $category->color ?? '#6B7280' }}">
                                            <i class="fas fa-tag text-white text-sm"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                            @if($category->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($category->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $category->products->count() }}</div>
                                    <div class="text-sm text-gray-500">productos</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->is_customizable)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Sí
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-times mr-1"></i>
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Activa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Inactiva
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $category->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $category->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.categories.show', $category) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition duration-200"
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 p-2 rounded-lg hover:bg-yellow-50 transition duration-200"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($category->is_customizable)
                                            <a href="{{ route('admin.categories.customization-options', $category) }}" 
                                               class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-50 transition duration-200"
                                               title="Configurar opciones">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                        @endif
                                        @if($category->products->count() == 0)
                                            <button type="button" 
                                                    onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}')"
                                                    class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition duration-200"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-tags"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay categorías</h3>
                <p class="text-gray-500 mb-6">Comienza creando tu primera categoría de productos.</p>
                <a href="{{ route('admin.categories.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Nueva Categoría
                </a>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal Script -->
<script>
function confirmDelete(categoryId, categoryName) {
    if (confirm(`¿Estás seguro de que deseas eliminar la categoría "${categoryName}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/categories/${categoryId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection