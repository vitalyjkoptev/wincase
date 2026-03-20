/**
 * WinCase CRM — API Helper
 * Used by all blade views to fetch real data from /api/v1/*
 */
const CRM = {
    BASE: '/api/v1',
    token: localStorage.getItem('wc_token'),

    get headers() {
        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + this.token,
        };
    },

    async get(url) {
        try {
            const r = await fetch(this.BASE + url, { headers: this.headers });
            if (r.status === 401) { window.location.href = '/login'; return null; }
            if (!r.ok) return null;
            const j = await r.json();
            return j.data !== undefined ? j.data : j;
        } catch (e) { console.warn('API error:', url, e); return null; }
    },

    async post(url, body = {}) {
        try {
            const r = await fetch(this.BASE + url, { method: 'POST', headers: this.headers, body: JSON.stringify(body) });
            if (r.status === 401) { window.location.href = '/login'; return null; }
            const j = await r.json();
            return j;
        } catch (e) { console.warn('API error:', url, e); return null; }
    },

    async put(url, body = {}) {
        try {
            const r = await fetch(this.BASE + url, { method: 'PUT', headers: this.headers, body: JSON.stringify(body) });
            if (r.status === 401) { window.location.href = '/login'; return null; }
            const j = await r.json();
            return j;
        } catch (e) { console.warn('API error:', url, e); return null; }
    },

    async del(url) {
        try {
            const r = await fetch(this.BASE + url, { method: 'DELETE', headers: this.headers });
            if (r.status === 401) { window.location.href = '/login'; return null; }
            return r.ok;
        } catch (e) { return false; }
    },

    // Formatters
    fmt(n) { return n != null ? Number(n).toLocaleString('pl-PL') : '0'; },
    fmtPLN(n) { return this.fmt(n) + ' PLN'; },
    fmtDate(d) { return d ? new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : ''; },
    fmtDateShort(d) { return d ? new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : ''; },
    fmtTime(d) { return d ? new Date(d).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) : ''; },

    ago(d) {
        if (!d) return '';
        const diff = Math.floor((Date.now() - new Date(d)) / 60000);
        if (diff < 1) return 'Just now';
        if (diff < 60) return diff + ' min ago';
        if (diff < 1440) return Math.floor(diff / 60) + 'h ago';
        return Math.floor(diff / 1440) + 'd ago';
    },

    badgeColor(status) {
        const m = {
            new: 'warning', contacted: 'primary', consultation: 'info', converted: 'success',
            paid: 'success', rejected: 'danger', active: 'success', inactive: 'secondary',
            submitted: 'primary', in_progress: 'info', pending: 'warning', completed: 'success',
            closed: 'secondary', cancelled: 'danger', overdue: 'danger',
            urgent: 'danger', high: 'danger', medium: 'warning', low: 'info', normal: 'info',
            approved: 'success', pending_review: 'warning', draft: 'secondary',
        };
        return m[(status || '').toLowerCase()] || 'secondary';
    },

    badge(status) {
        const c = this.badgeColor(status);
        const label = (status || '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        return `<span class="badge bg-${c}-subtle text-${c}">${label}</span>`;
    },

    initials(name) {
        return (name || '??').split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
    },

    avatar(name, size = 'sm') {
        const colors = ['primary', 'success', 'warning', 'info', 'danger'];
        const hash = (name || '').split('').reduce((a, c) => a + c.charCodeAt(0), 0);
        const color = colors[hash % colors.length];
        return `<div class="avatar avatar-${size} avatar-rounded bg-${color}-subtle text-${color} d-flex align-items-center justify-content-center fw-semibold">${this.initials(name)}</div>`;
    },

    // Toast notification
    toast(msg, type = 'success') {
        const el = document.createElement('div');
        el.className = `alert alert-${type} position-fixed top-0 end-0 m-3 shadow-lg`;
        el.style.zIndex = '9999';
        el.innerHTML = msg;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), 3000);
    },

    // Loading spinner
    loader(el) {
        if (typeof el === 'string') el = document.querySelector(el);
        if (el) el.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm"></div></div>';
    },
};
