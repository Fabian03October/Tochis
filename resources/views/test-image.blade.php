<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Upload de Imágenes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">Prueba de Upload de Imágenes</h1>
        
        <form id="upload-form" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Seleccionar Imagen
                </label>
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Subir Imagen
            </button>
        </form>
        
        <div id="result" class="mt-6 hidden">
            <h3 class="text-lg font-semibold mb-2">Resultado:</h3>
            <pre id="result-content" class="bg-gray-100 p-3 rounded text-sm overflow-auto"></pre>
        </div>
        
        <div id="preview" class="mt-6 hidden">
            <h3 class="text-lg font-semibold mb-2">Vista previa:</h3>
            <img id="preview-img" src="" alt="Preview" class="max-w-full h-auto rounded">
        </div>
    </div>

    <script>
        document.getElementById('upload-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const imageFile = document.getElementById('image').files[0];
            
            if (!imageFile) {
                alert('Por favor selecciona una imagen');
                return;
            }
            
            formData.append('image', imageFile);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/test-image-upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').classList.remove('hidden');
                document.getElementById('result-content').textContent = JSON.stringify(data, null, 2);
                
                if (data.status === 'success' && data.full_url) {
                    document.getElementById('preview').classList.remove('hidden');
                    document.getElementById('preview-img').src = data.full_url;
                }
            })
            .catch(error => {
                document.getElementById('result').classList.remove('hidden');
                document.getElementById('result-content').textContent = 'Error: ' + error.message;
            });
        });
        
        // Preview antes de subir
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').classList.remove('hidden');
                    document.getElementById('preview-img').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
