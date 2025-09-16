export function parseDateLocal(fecha) {
    if (!fecha) return null;
    if (fecha instanceof Date) return fecha;
    if (/^\d{4}-\d{2}-\d{2}$/.test(fecha)) {
        const [y, m, d] = fecha.split('-').map(Number);
        return new Date(y, m - 1, d);
    }

    // ISO con Z y microsegundos: 2025-09-16T00:00:00.000000Z
    if (/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?Z$/.test(fecha)) {
        const d = new Date(fecha);
        if (!isNaN(d.getTime())) {
            return new Date(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate());
        }
    }

    let dt = new Date(fecha);
    if (!isNaN(dt.getTime())) return dt;
    dt = new Date(fecha + 'T00:00:00');
    return isNaN(dt.getTime()) ? null : dt;
}

export function formatDateShort(fecha) {
    const dt = parseDateLocal(fecha);
    if (!dt) return 'No especificada';
    return dt.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

export function formatDateLong(fecha, hora) {
    const dt = parseDateLocal(fecha);
    if (!dt) return 'Fecha no especificada';

    let dtWithTime = dt;
    if (hora) {
        const [hh = 0, mm = 0] = hora.split(':').map(Number);
        dtWithTime = new Date(dt.getFullYear(), dt.getMonth(), dt.getDate(), hh, mm);
    }

    return dtWithTime.toLocaleDateString('es-PE', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' }) + (hora ? ` a las ${hora}` : '');
}
