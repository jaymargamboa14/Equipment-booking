function debounce(fn, delay = 350) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

export function initBookingForm() {
    const form = document.getElementById('booking-form');

    if (!form) {
        return;
    }

    const container = document.getElementById('items-container');
    const template = document.getElementById('item-row-template');
    const addButton = document.getElementById('add-item');
    const globalErrors = document.getElementById('items-errors');
    const availabilityUrl = form.dataset.availabilityUrl;
    const equipment = JSON.parse(form.dataset.equipment || '[]');
    const equipmentById = new Map(equipment.map((item) => [String(item.id), item]));

    const dateInput = form.querySelector('[name="event_date"]');
    const startInput = form.querySelector('[name="start_time"]');
    const endInput = form.querySelector('[name="end_time"]');

    const rows = () => Array.from(container.querySelectorAll('.item-row'));

    const reindex = () => {
        rows().forEach((row, index) => {
            row.querySelector('.item-select').setAttribute('name', `items[${index}][equipment_id]`);
            row.querySelector('.item-quantity').setAttribute('name', `items[${index}][quantity]`);
        });
    };

    const setMessage = (row, message, tone) => {
        const target = row.querySelector('.availability-text');
        target.textContent = message || '';
        target.className = `availability-text text-xs ${
            tone === 'error' ? 'text-red-600' : tone === 'success' ? 'text-green-600' : 'text-gray-400'
        }`;
    };

    const updateRow = async (row) => {
        const select = row.querySelector('.item-select');
        const quantityInput = row.querySelector('.item-quantity');
        const equipmentId = select.value;

        if (!equipmentId || !dateInput.value || !startInput.value || !endInput.value) {
            setMessage(row, '', null);
            return;
        }

        const selected = equipmentById.get(String(equipmentId));
        const params = new URLSearchParams({
            equipment_id: equipmentId,
            event_date: dateInput.value,
            start_time: startInput.value,
            end_time: endInput.value,
        });

        if (selected) {
            const remaining = selected.quantity - Number(quantityInput.value || 0);
            setMessage(row, `Checking availability...`, null);
        }

        try {
            const response = await fetch(`${availabilityUrl}?${params.toString()}`, {
                headers: { Accept: 'application/json' },
            });

            if (!response.ok) {
                setMessage(row, 'Could not check availability.', null);
                return;
            }

            const data = await response.json();
            const requested = Number(quantityInput.value || 0);

            if (requested > data.available) {
                setMessage(row, `Only ${data.available} of ${data.total} available for this time.`, 'error');
            } else {
                setMessage(row, `${data.available} of ${data.total} available for this time.`, 'success');
            }
        } catch (error) {
            setMessage(row, 'Network error checking availability.', null);
        }
    };

    const debouncedUpdate = debounce(updateRow);

    const bindRow = (row) => {
        row.querySelector('.item-select').addEventListener('change', () => updateRow(row));
        row.querySelector('.item-quantity').addEventListener('input', () => debouncedUpdate(row));
        row.querySelector('.remove-row').addEventListener('click', () => {
            if (rows().length <= 1) {
                return;
            }

            row.remove();
            reindex();
        });
    };

    const addRow = (preselectedId = null, quantity = null) => {
        const fragment = template.content.cloneNode(true);
        const row = fragment.firstElementChild;
        const select = row.querySelector('.item-select');

        equipment.forEach((item) => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.name} (${item.category}) — ${item.quantity} total`;
            select.appendChild(option);
        });

        container.appendChild(row);

        if (preselectedId) {
            select.value = String(preselectedId);
        }

        if (quantity) {
            row.querySelector('.item-quantity').value = quantity;
        }

        bindRow(row);
        reindex();
        updateRow(row);
    };

    addButton.addEventListener('click', () => {
        if (globalErrors) {
            globalErrors.textContent = '';
        }
        addRow();
    });

    [dateInput, startInput, endInput].forEach((input) => {
        input.addEventListener('change', () => rows().forEach((row) => updateRow(row)));
    });

    const oldItems = JSON.parse(form.dataset.oldItems || '[]');
    const preselect = form.dataset.preselect;

    if (oldItems.length) {
        oldItems.forEach((item) => addRow(item.equipment_id, item.quantity));
    } else {
        addRow(preselect || null);
    }
}
