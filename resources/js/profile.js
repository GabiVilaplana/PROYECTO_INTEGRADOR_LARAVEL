document.getElementById('foto_perfil_input')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('foto_perfil', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    fetch("{{ route('perfil.foto.actualizar') }}", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar la imagen sin recargar la página
            document.querySelector('.profile-dropdown-icon').src = data.url + '?t=' + Date.now();
            alert('Foto de perfil actualizada correctamente');
        } else {
            alert('Error al subir la imagen: ' + (data.message || 'Desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un problema al subir la imagen.');
    });
});