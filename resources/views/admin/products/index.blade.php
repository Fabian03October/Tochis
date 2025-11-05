@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 bg-orange-100 p-6 rounded-lg">
        <div>
            <h1 class="text-3xl font-bold text-orange-800">Productos</h1>
            <p class="text-orange-600 mt-1">Gestiona el inventario de productos</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="mt-4 sm:mt-0 bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Nuevo Producto
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-orange-600">{{ $products->total() }}</p>
                    <p class="text-gray-600">Total Productos</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-boxes text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ $products->where('is_active', true)->count() }}</p>
                    <p class="text-gray-600">Activos</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-red-600">{{ App\Models\Product::lowStock()->count() }}</p>
                    <p class="text-gray-600">Stock Bajo</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-orange-600">${{ number_format($products->sum('price'), 2) }}</p>
                    <p class="text-gray-600">Valor Total</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nombre o código..." 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                <select name="category" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-search mr-1"></i>
                    Buscar
                </button>
                @if(request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-times mr-1"></i>
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    @if($products->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Producto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categoría
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Precio
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stock
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center overflow-hidden">
                                            @if($product->image)
                                                <img src="{{ Storage::url($product->image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <i class="fas fa-box text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            @if($product->code)
                                                <div class="text-sm text-gray-500">{{ $product->code }}</div>
                                            @endif
                                            @if($product->description)
                                                <div class="text-xs text-gray-400">{{ Str::limit($product->description, 30) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                          style="background-color: {{ $product->category->color ?? '#E5E7EB' }}20; color: {{ $product->category->color ?? '#6B7280' }}">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${{ number_format($product->price, 2) }}</div>
                                    @if($product->compare_price > 0)
                                        <div class="text-xs text-gray-500 line-through">${{ number_format($product->compare_price, 2) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->manage_stock)
                                        <div class="text-sm text-gray-900">{{ $product->stock }}</div>
                                        @if($product->stock <= $product->min_stock)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Stock Bajo
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-500">No gestionado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.products.show', $product) }}" 
                                           class="text-orange-600 hover:text-orange-900 p-1" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="text-green-600 hover:text-green-900 p-1" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')" 
                                                class="text-red-600 hover:text-red-900 p-1" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-box text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                @if(request()->hasAny(['search', 'category', 'status']))
                    No se encontraron productos
                @else
                    No hay productos registrados
                @endif
            </h3>
            <p class="text-gray-500 mb-6">
                @if(request()->hasAny(['search', 'category', 'status']))
                    Intenta ajustar los filtros de búsqueda o crear un nuevo producto.
                @else
                    Comienza agregando tu primer producto al inventario.
                @endif
            </p>
            
            @if(request()->hasAny(['search', 'category', 'status']))
                <div class="space-x-4">
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Limpiar Filtros
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Producto
                    </a>
                </div>
            @else
                <a href="{{ route('admin.products.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Crear mi primer producto
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal Script -->
<script>
function confirmDelete(productId, productName) {
    if (confirm(`¿Estás seguro de que deseas eliminar el producto "${productName}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/products/${productId}`;
        
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