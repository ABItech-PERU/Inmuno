import { parseDateLocal } from './date.js';

export function formatHM(date) {
    const hh = String(date.getHours()).padStart(2, '0');
    const mm = String(date.getMinutes()).padStart(2, '0');
    return `${hh}:${mm}`;
}

export function validateDateTime(fechaStr, horaStr, now = new Date()) {
    if (!fechaStr) return { valid: true, assignedTime: null, error: null };

    const fecha = parseDateLocal(fechaStr);
    if (!fecha) return { valid: false, assignedTime: null, error: 'Fecha inválida' };

    const isHoy = fecha.getFullYear() === now.getFullYear() && fecha.getMonth() === now.getMonth() && fecha.getDate() === now.getDate();

    if (!isHoy) return { valid: true, assignedTime: null, error: null };

    // Si es hoy
    if (!horaStr) {
        const assigned = new Date(now.getTime() + 1 * 60 * 1000);
        return { valid: true, assignedTime: formatHM(assigned), error: null };
    }

    const [hh, mm] = horaStr.split(':').map(Number);
    const seleccionado = new Date(fecha.getFullYear(), fecha.getMonth(), fecha.getDate(), hh || 0, mm || 0, 0, 0);
    const minPermitido = new Date(now.getTime() + 1 * 60 * 1000);
    if (seleccionado < minPermitido) {
        return { valid: false, assignedTime: null, error: 'La hora debe ser al menos 2 minutos más tarde.' };
    }

    return { valid: true, assignedTime: null, error: null };
}
