document.addEventListener('DOMContentLoaded', function () {
    // ======== SUBIR Y ACTUALIZAR FOTO DE PERFIL ========
    const fotoInput = document.getElementById('foto_perfil_input');
    if (fotoInput) {
        const previewImgs = document.querySelectorAll('.profile-dropdown-icon, .profile-avatar');
        const previewBackgrounds = document.querySelectorAll('.profile-background');

        fotoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            // 🔹 Preview inmediato antes de subir
            const reader = new FileReader();
            reader.onload = function (ev) {
                const url = ev.target.result;
                previewImgs.forEach(img => img.src = url);
                previewBackgrounds.forEach(el => el.style.backgroundImage = `url(${url})`);
            };
            reader.readAsDataURL(file);

            // Subida al servidor
            const formData = new FormData();
            formData.append('foto_perfil', file);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const url = window.routes?.actualizarFotoPerfil;
            if (!url) {
                console.error('Ruta de actualización de foto no definida');
                alert('Error: ruta de subida no configurada');
                return;
            }

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) return response.json().then(err => { throw err; });
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.url) {
                        // Actualizar con la URL definitiva + timestamp
                        previewImgs.forEach(img => img.src = data.url + '?t=' + Date.now());
                        previewBackgrounds.forEach(el => el.style.backgroundImage = `url(${data.url}?t=${Date.now()})`);
                        alert('Foto de perfil actualizada correctamente');
                    } else {
                        alert('Error al subir la imagen: ' + (data.message || 'Desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error al subir la imagen:', error);
                    alert(error?.message || 'Hubo un problema al subir la imagen.');
                });
        });
    }

    // ======== TABS INTERACTIVAS ========
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

            button.classList.add('active');
            const tabId = button.getAttribute('data-tab');
            const targetPane = document.getElementById(`tab-${tabId}`);
            if (targetPane) targetPane.classList.add('active');
        });
    });

    // ======== TOGGLE DE SERVICIO (sin recargar la página) ========
    document.addEventListener('submit', function (e) {
        if (e.target.matches('.toggle-servicio-form')) {
            e.preventDefault();

            const form = e.target;
            const url = form.action;

            fetch(url, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const button = form.querySelector('button');
                        const isActive = data.activo;
                        button.innerHTML = `<span>${isActive ? '❌' : '✅'}</span> ${isActive ? 'Deshabilitar' : 'Habilitar'}`;
                        button.className = `btn-action ${isActive ? 'btn-delete' : 'btn-activate'}`;
                    } else {
                        alert('Error: ' + (data.message || 'No autorizado'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('No se pudo actualizar el servicio.');
                });
        }
    });
});
