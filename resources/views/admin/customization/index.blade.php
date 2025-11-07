@extends('layouts.app')

@section('title', 'Personalización de Platillos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Personalización de Platillos</h1>
    </div>

    <!-- Configuración de Categorías -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Configuración de Categorías</h2>
            <p class="text-sm text-gray-600">Define qué categorías permiten personalización de Platillos</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categories as $category)
                <div class="border rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $category->description }}</p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="category_{{ $category->id }}" 
                                   class="category-toggle h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   data-category-id="{{ $category->id }}"
                                   {{ $category->is_customizable ? 'checked' : '' }}>
                            <label for="category_{{ $category->id }}" class="ml-2 text-sm text-gray-700">
                                Personalizable
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Gestión de Observaciones -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Observaciones</h2>
                    <p class="text-sm text-gray-600">Elementos que se pueden quitar de los Platillos</p>
                </div>
                <button onclick="openAddModal('observation')" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Agregar Observación
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="observations-grid">
                @foreach($observations as $observation)
                <div class="border rounded-lg p-4 observation-item" data-id="{{ $observation->id }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $observation->name }}</h3>
                            <p class="text-sm text-gray-500">Orden: {{ $observation->sort_order }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editOption({{ $observation->id }}, '{{ $observation->name }}', {{ $observation->price }}, {{ $observation->sort_order }}, '{{ $observation->type }}')"
                                    class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteOption({{ $observation->id }})"
                                    class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Gestión de Especialidades -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Especialidades</h2>
                    <p class="text-sm text-gray-600">Elementos que se pueden agregar a los Platillos (con costo adicional)</p>
                </div>
                <button onclick="openAddModal('specialty')" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Agregar Especialidad
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="specialties-grid">
                @foreach($specialties as $specialty)
                <div class="border rounded-lg p-4 specialty-item" data-id="{{ $specialty->id }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $specialty->name }}</h3>
                            <p class="text-sm text-gray-600">Precio: ${{ number_format($specialty->price, 2) }}</p>
                            <p class="text-sm text-gray-500">Orden: {{ $specialty->sort_order }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editOption({{ $specialty->id }}, '{{ $specialty->name }}', {{ $specialty->price }}, {{ $specialty->sort_order }}, '{{ $specialty->type }}')"
                                    class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteOption({{ $specialty->id }})"
                                    class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Opción -->
<div id="optionModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="optionForm">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalTitle">
                                Agregar Opción
                            </h3>
                            
                            <input type="hidden" id="optionId">
                            <input type="hidden" id="optionType">
                            
                            <div class="mb-4">
                                <label for="optionName" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="optionName" name="name" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div class="mb-4" id="priceField">
                                <label for="optionPrice" class="block text-sm font-medium text-gray-700">Precio</label>
                                <input type="number" id="optionPrice" name="price" step="0.01" min="0"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div class="mb-4">
                                <label for="optionSortOrder" class="block text-sm font-medium text-gray-700">Orden</label>
                                <input type="number" id="optionSortOrder" name="sort_order" min="0"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Manejar cambios en categorías
document.querySelectorAll('.category-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const categoryId = this.dataset.categoryId;
        const isCustomizable = this.checked;
        
        fetch('/admin/customization/category', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                category_id: categoryId,
                is_customizable: isCustomizable
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al actualizar la configuración', 'error');
            this.checked = !this.checked; // Revertir cambio
        });
    });
});

function openAddModal(type) {
    document.getElementById('modalTitle').textContent = type === 'observation' ? 'Agregar Observación' : 'Agregar Especialidad';
    document.getElementById('optionId').value = '';
    document.getElementById('optionType').value = type;
    document.getElementById('optionName').value = '';
    document.getElementById('optionPrice').value = type === 'observation' ? '0' : '';
    document.getElementById('optionSortOrder').value = '';
    
    // Mostrar/ocultar campo precio según el tipo
    const priceField = document.getElementById('priceField');
    if (type === 'observation') {
        priceField.style.display = 'none';
    } else {
        priceField.style.display = 'block';
    }
    
    document.getElementById('optionModal').classList.remove('hidden');
}

function editOption(id, name, price, sortOrder, type) {
    document.getElementById('modalTitle').textContent = type === 'observation' ? 'Editar Observación' : 'Editar Especialidad';
    document.getElementById('optionId').value = id;
    document.getElementById('optionType').value = type;
    document.getElementById('optionName').value = name;
    document.getElementById('optionPrice').value = price;
    document.getElementById('optionSortOrder').value = sortOrder;
    
    // Mostrar/ocultar campo precio según el tipo
    const priceField = document.getElementById('priceField');
    if (type === 'observation') {
        priceField.style.display = 'none';
    } else {
        priceField.style.display = 'block';
    }
    
    document.getElementById('optionModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('optionModal').classList.add('hidden');
}

function deleteOption(id) {
    if (confirm('¿Estás seguro de que quieres eliminar esta opción?')) {
        fetch(`/admin/customization/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al eliminar la opción', 'error');
        });
    }
}

// Manejar formulario
document.getElementById('optionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('optionId').value;
    const formData = new FormData();
    formData.append('name', document.getElementById('optionName').value);
    formData.append('type', document.getElementById('optionType').value);
    formData.append('price', document.getElementById('optionPrice').value || 0);
    formData.append('sort_order', document.getElementById('optionSortOrder').value || 0);
    
    const url = id ? `/admin/customization/${id}` : '/admin/customization';
    const method = id ? 'PUT' : 'POST';
    
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            closeModal();
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error al guardar la opción', 'error');
    });
});

function showAlert(message, type) {
    // Crear elemento de alerta
    const alert = document.createElement('div');
    alert.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    alert.textContent = message;
    
    document.body.appendChild(alert);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        alert.remove();
    }, 3000);
}
</script>
@endpush
@endsection
