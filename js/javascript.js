document.getElementById('id_categoria').addEventListener('change', async function () {
        const idCategoria = this.value;
        const roomSelect  = document.getElementById('room_select');

        if (!idCategoria) {
            roomSelect.innerHTML = '<option value="">Selecciona una categoría...</option>';
            actualizarResumen();
            return;
        }

        roomSelect.innerHTML = '<option value="">Cargando...</option>';

        try {
            const response    = await fetch(`index.php?action=AJAXCategoria&id_categoria=${idCategoria}`);
            const habitaciones = await response.json();

            roomSelect.innerHTML = '<option value="">Selecciona una habitación...</option>';
            habitaciones.forEach(h => {
                roomSelect.innerHTML += `<option value="${h.id}" data-precio="${h.precio}" data-categoria="${h.id_categoria}">
                    Hab ${h.num_habitacion} - ${h.name} ($${Number(h.precio).toLocaleString('es-CO')})
                </option>`;
            });
        } catch (error) {
            roomSelect.innerHTML = '<option value="">Error al cargar</option>';
            console.log("fallo", error);
        }

        actualizarResumen();
    });

    function actualizarResumen() {
        const roomSelect  = document.getElementById('room_select');
        const selectedOpt = roomSelect.options[roomSelect.selectedIndex];
        const precio      = parseFloat(selectedOpt?.dataset?.precio || 0);

        const fi = document.getElementById('fecha_inicio').value;
        const ff = document.getElementById('fecha_fin').value;
        const np = parseInt(document.getElementById('num_personas').value || 1);

        let noches = 0;
        if (fi && ff) {
            const diff = (new Date(ff) - new Date(fi)) / 86400000;
            noches = diff > 0 ? diff : 0;
        }

        const cargoPersonas = np >= 2 ? (np - 1) * 10000 : 0;
        const total = (precio + cargoPersonas) * noches ;
        const fmt = v => '$' + Math.round(v).toLocaleString('es-CO');

        document.getElementById('res-precio-noche').textContent   = fmt(precio);
        document.getElementById('res-noches').textContent         = noches + (noches === 1 ? ' noche' : ' noches');
        document.getElementById('res-cargo-personas').textContent = fmt(cargoPersonas);
        document.getElementById('res-total').textContent          = fmt(total);
    }

    ['fecha_inicio','fecha_fin','num_personas','room_select'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', actualizarResumen);
        document.getElementById(id)?.addEventListener('input',  actualizarResumen);
    });

    actualizarResumen();