<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Contatos</title>
    <style>
        :root {
            --bg-deep: #070b14;
            --bg-mid: #0c1224;
            --surface: rgba(18, 16, 38, 0.72);
            --surface-2: rgba(12, 18, 36, 0.85);
            --line: rgba(139, 92, 246, 0.28);
            --line-soft: rgba(99, 102, 241, 0.15);
            --text: #f4f2fb;
            --muted: #b8a8d9;
            --accent: #a78bfa;
            --accent-strong: #8b5cf6;
            --accent-hover: #7c3aed;
            --accent-glow: rgba(139, 92, 246, 0.25);
            --danger: #fb7185;
            --radius: 10px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            line-height: 1.5;
            color: var(--text);
            background-color: var(--bg-deep);
            background-image:
                radial-gradient(ellipse 120% 70% at 50% -25%, rgba(109, 40, 217, 0.45) 0%, transparent 50%),
                radial-gradient(ellipse 80% 50% at 100% 50%, rgba(30, 58, 138, 0.35) 0%, transparent 45%),
                radial-gradient(ellipse 60% 40% at 0% 80%, rgba(88, 28, 135, 0.25) 0%, transparent 40%),
                linear-gradient(165deg, #0a0f1c 0%, #0d0820 40%, #061018 100%);
            background-attachment: fixed;
        }
        .wrap {
            max-width: 960px;
            margin: 0 auto;
            padding: 2.5rem 1.25rem 3rem;
        }
        header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--line);
        }
        h1 {
            font-size: 1.35rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin: 0;
            color: var(--text);
        }
        h1 .accent { color: var(--accent); }
        .sub { margin: 0.25rem 0 0; font-size: 0.8125rem; color: var(--muted); }
        .card {
            background: var(--surface);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 1.25rem 1.35rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.25);
        }
        .section-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin: 0 0 1rem;
        }
        label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.35rem;
        }
        input {
            width: 100%;
            padding: 0.5rem 0.65rem;
            border-radius: 6px;
            border: 1px solid var(--line);
            background: var(--surface-2);
            color: var(--text);
            font: inherit;
            margin-bottom: 0.9rem;
        }
        input::placeholder { color: rgba(184, 168, 217, 0.45); }
        input:focus {
            outline: none;
            border-color: var(--accent-strong);
            box-shadow: 0 0 0 2px var(--accent-glow);
        }
        .row { display: grid; gap: 0.75rem 1rem; }
        @media (min-width: 640px) {
            .row.cols-3 { grid-template-columns: repeat(3, 1fr); }
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
            margin-top: 0.25rem;
        }
        button, .btn {
            font: inherit;
            font-weight: 500;
            font-size: 0.8125rem;
            padding: 0.45rem 0.85rem;
            border-radius: 6px;
            border: 1px solid transparent;
            cursor: pointer;
            background: transparent;
        }
        button:disabled { opacity: 0.45; cursor: not-allowed; }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-strong) 0%, #6d28d9 100%);
            color: #fff;
            border-color: transparent;
        }
        .btn-primary:hover:not(:disabled) {
            filter: brightness(1.08);
            box-shadow: 0 0 20px var(--accent-glow);
        }
        .btn-ghost {
            color: var(--muted);
            border-color: var(--line);
            background: var(--line-soft);
        }
        .btn-ghost:hover:not(:disabled) {
            background: rgba(139, 92, 246, 0.18);
            color: var(--text);
        }
        .btn-danger {
            color: var(--danger);
            border-color: rgba(251, 113, 133, 0.35);
            background: rgba(127, 29, 29, 0.25);
        }
        .btn-danger:hover:not(:disabled) {
            background: rgba(190, 18, 60, 0.35);
        }
        .btn-muted {
            color: var(--text);
            border-color: var(--line);
            background: rgba(99, 102, 241, 0.12);
        }
        .btn-muted:hover:not(:disabled) {
            background: rgba(139, 92, 246, 0.22);
        }
        .btn-sm { padding: 0.3rem 0.5rem; font-size: 0.75rem; }
        .alert {
            padding: 0.65rem 0.85rem;
            border-radius: 6px;
            margin-bottom: 0.75rem;
            font-size: 0.8125rem;
        }
        .alert-error {
            background: rgba(127, 29, 29, 0.35);
            border: 1px solid rgba(251, 113, 133, 0.4);
            color: #fecdd3;
        }
        .alert-success {
            background: rgba(6, 78, 59, 0.4);
            border: 1px solid rgba(52, 211, 153, 0.35);
            color: #bbf7d0;
        }
        .table-scroll { overflow-x: auto; margin: 0 -0.15rem; }
        table { width: 100%; border-collapse: collapse; font-size: 0.8125rem; }
        th, td {
            text-align: left;
            padding: 0.65rem 0.5rem;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }
        th {
            color: var(--muted);
            font-weight: 500;
            font-size: 0.75rem;
        }
        tbody tr:hover { background: rgba(139, 92, 246, 0.1); }
        tr:last-child td { border-bottom: none; }
        .badge {
            display: inline-block;
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
            font-size: 0.6875rem;
            font-weight: 500;
        }
        .badge-pending { background: rgba(251, 191, 36, 0.18); color: #fcd34d; }
        .badge-done { background: rgba(52, 211, 153, 0.18); color: #6ee7b7; }
        .badge-fail { background: rgba(248, 113, 113, 0.18); color: #fca5a5; }
        .empty { color: var(--muted); text-align: center; padding: 2rem 1rem; font-size: 0.875rem; }
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .toolbar .section-label { margin: 0; }
        .meta { font-size: 0.75rem; color: var(--muted); }
        code {
            font-size: 0.75em;
            font-family: ui-monospace, monospace;
            background: rgba(0, 0, 0, 0.35);
            padding: 0.1rem 0.35rem;
            border-radius: 4px;
            color: #ddd6fe;
            border: 1px solid var(--line-soft);
        }
        .col-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            justify-content: flex-end;
            align-items: center;
        }
        th.col-actions { text-align: right; }
        .name-cell { font-weight: 500; color: #faf5ff; }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            background: rgba(2, 4, 12, 0.78);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.22s ease, visibility 0.22s ease;
        }
        .modal-backdrop.is-open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        .modal-panel {
            width: 100%;
            max-width: 440px;
            max-height: min(90vh, 520px);
            overflow: auto;
            background: linear-gradient(168deg, rgba(26, 22, 48, 0.98) 0%, rgba(12, 14, 28, 0.98) 55%, rgba(10, 12, 24, 0.99) 100%);
            border: 1px solid rgba(139, 92, 246, 0.35);
            border-radius: 16px;
            box-shadow:
                0 0 0 1px rgba(167, 139, 250, 0.08),
                0 28px 56px rgba(0, 0, 0, 0.55),
                0 0 100px rgba(91, 33, 182, 0.18);
            transform: scale(0.94) translateY(12px);
            opacity: 0;
            transition:
                transform 0.26s cubic-bezier(0.34, 1.35, 0.64, 1),
                opacity 0.22s ease;
        }
        .modal-backdrop.is-open .modal-panel {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
        .modal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.35rem 1.35rem 0.75rem;
            border-bottom: 1px solid var(--line-soft);
        }
        .modal-title {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--text);
            line-height: 1.35;
        }
        .modal-title .modal-title-accent {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .modal-close {
            flex-shrink: 0;
            width: 2.25rem;
            height: 2.25rem;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 1.35rem;
            line-height: 1;
            color: var(--muted);
        }
        .modal-close:hover {
            color: var(--text);
            background: rgba(139, 92, 246, 0.15);
        }
        .modal-body {
            margin: 0;
            padding: 1.15rem 1.35rem 1.35rem;
            font-size: 0.9375rem;
            line-height: 1.55;
            color: #e4dcfa;
        }
        .modal-body strong {
            color: #faf5ff;
            font-weight: 600;
        }
        .modal-foot {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 0.6rem;
            padding: 0 1.35rem 1.35rem;
        }
        .modal-foot .btn {
            min-width: 7.5rem;
            padding: 0.55rem 1rem;
            font-size: 0.875rem;
        }
        .modal-head-inner {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        .modal-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            background: rgba(251, 113, 133, 0.12);
            border: 1px solid rgba(251, 113, 133, 0.28);
            flex-shrink: 0;
        }
        .modal-title-wrap { min-width: 0; }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <div>
                <h1>{{ config('app.name') }} <span class="accent">· Contatos</span></h1>
                <p class="sub">Cadastre, edite e pontue seus contatos</p>
            </div>
            <button type="button" class="btn btn-ghost" id="btn-refresh">Atualizar</button>
        </header>

        <div id="flash"></div>

        <section class="card">
            <p class="section-label" id="form-title">Novo contato</p>
            <form id="form-contact">
                <input type="hidden" id="edit-id" value="">
                <div class="row cols-3">
                    <div>
                        <label for="name">Nome</label>
                        <input id="name" name="name" required maxlength="255" autocomplete="name" inputmode="text">
                    </div>
                    <div>
                        <label for="email">E-mail</label>
                        <input id="email" name="email" type="email" required maxlength="255" autocomplete="email" inputmode="email">
                    </div>
                    <div>
                        <label for="phone">Telefone</label>
                        <input id="phone" name="phone" type="tel" required maxlength="15" autocomplete="tel" inputmode="tel" placeholder="(11) 98765-4321">
                    </div>
                </div>
                <div class="actions">
                    <button type="submit" class="btn btn-primary" id="btn-submit">Salvar</button>
                    <button type="button" class="btn btn-ghost" id="btn-cancel" hidden>Cancelar edição</button>
                </div>
            </form>
        </section>

        <section class="card">
            <div class="toolbar">
                <p class="section-label">Contatos</p>
                <span class="meta" id="list-meta"></span>
            </div>
            <div id="table-wrap">
                <p class="empty">Um momento…</p>
            </div>
        </section>
    </div>

    <div id="modal-backdrop" class="modal-backdrop" role="presentation" aria-hidden="true">
        <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1">
            <div class="modal-head">
                <div class="modal-head-inner">
                    <div class="modal-icon" id="modal-icon" aria-hidden="true">⚠</div>
                    <div class="modal-title-wrap">
                        <h2 id="modal-title" class="modal-title"></h2>
                    </div>
                </div>
                <button type="button" class="btn btn-ghost modal-close" id="modal-btn-close" aria-label="Fechar">×</button>
            </div>
            <p class="modal-body" id="modal-body"></p>
            <div class="modal-foot">
                <button type="button" class="btn btn-ghost" id="modal-btn-cancel">Cancelar</button>
                <button type="button" class="btn btn-primary" id="modal-btn-confirm">Confirmar</button>
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    @php
        $contactBroadcastConfig = [
            'key' => config('broadcasting.connections.reverb.key'),
            'wsHost' => config('broadcasting.connections.reverb.options.host'),
            'wsPort' => (int) config('broadcasting.connections.reverb.options.port'),
            'wssPort' => (int) config('broadcasting.connections.reverb.options.port'),
            'scheme' => config('broadcasting.connections.reverb.options.scheme'),
        ];
    @endphp
    <script>
        window.__contactBroadcast = @json($contactBroadcastConfig);
        const API = '/api/contacts';
        const CAMPOS_PT = { name: 'Nome', email: 'E-mail', phone: 'Telefone' };

        const el = (id) => document.getElementById(id);
        const flash = el('flash');
        const tableWrap = el('table-wrap');
        const listMeta = el('list-meta');
        const form = el('form-contact');
        const editId = el('edit-id');
        const formTitle = el('form-title');
        const btnCancel = el('btn-cancel');
        const phoneInput = el('phone');
        const emailInput = el('email');
        const nameInput = el('name');

        let scorePusher = null;
        const scoreChannelHandles = [];

        function disconnectScoreBroadcast() {
            scoreChannelHandles.forEach((ch) => {
                try {
                    ch.unbind_all();
                } catch {}
            });
            scoreChannelHandles.length = 0;
            if (scorePusher) {
                try {
                    scorePusher.disconnect();
                } catch {}
                scorePusher = null;
            }
        }

        let scoreReloadTimer = null;
        function scheduleReloadFromScoreBroadcast() {
            clearTimeout(scoreReloadTimer);
            scoreReloadTimer = setTimeout(() => { loadContacts(); }, 400);
        }

        function connectScoreBroadcast(contactIds) {
            disconnectScoreBroadcast();
            const cfg = window.__contactBroadcast;
            if (!cfg || !cfg.key || typeof Pusher === 'undefined' || !contactIds.length) {
                return;
            }
            try {
                scorePusher = new Pusher(cfg.key, {
                    wsHost: cfg.wsHost,
                    wsPort: cfg.wsPort,
                    wssPort: cfg.wssPort,
                    forceTLS: cfg.scheme === 'https',
                    enabledTransports: ['ws', 'wss'],
                    cluster: '',
                    disableStats: true,
                });
                contactIds.forEach((id) => {
                    const ch = scorePusher.subscribe('contacts.' + id);
                    ch.bind('ContactScoreProcessed', scheduleReloadFromScoreBroadcast);
                    scoreChannelHandles.push(ch);
                });
            } catch {}
        }

        function escapeHtml(s) {
            const d = document.createElement('div');
            d.textContent = s;
            return d.innerHTML;
        }

        const modalBackdrop = el('modal-backdrop');
        const modalTitle = el('modal-title');
        const modalBody = el('modal-body');
        const modalIcon = el('modal-icon');
        const modalBtnCancel = el('modal-btn-cancel');
        const modalBtnConfirm = el('modal-btn-confirm');
        const modalBtnClose = el('modal-btn-close');
        let modalKeyHandler = null;
        let modalResolve = null;

        function closeModal(result) {
            modalBackdrop.classList.remove('is-open');
            modalBackdrop.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            if (modalKeyHandler) {
                document.removeEventListener('keydown', modalKeyHandler);
                modalKeyHandler = null;
            }
            const fn = modalResolve;
            modalResolve = null;
            if (fn) fn(result);
        }

        function openConfirmModal({ title, subtitle, bodyHtml, confirmLabel, danger, icon }) {
            return new Promise((resolve) => {
                modalResolve = resolve;
                modalTitle.innerHTML = escapeHtml(title) + (subtitle
                    ? `<span class="modal-title-accent">${escapeHtml(subtitle)}</span>`
                    : '');
                modalBody.innerHTML = bodyHtml;
                modalIcon.textContent = icon || (danger ? '⚠' : '✦');
                modalIcon.style.display = danger ? 'flex' : 'none';
                modalBtnConfirm.textContent = confirmLabel || 'Confirmar';
                modalBtnConfirm.className = 'btn ' + (danger ? 'btn-danger' : 'btn-primary');
                modalBackdrop.classList.add('is-open');
                modalBackdrop.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                modalKeyHandler = (e) => {
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        closeModal(false);
                    }
                };
                document.addEventListener('keydown', modalKeyHandler);
                requestAnimationFrame(() => modalBtnCancel.focus());
            });
        }

        modalBtnCancel.addEventListener('click', () => closeModal(false));
        modalBtnClose.addEventListener('click', () => closeModal(false));
        modalBtnConfirm.addEventListener('click', () => closeModal(true));
        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) closeModal(false);
        });

        function phoneDigits(str) {
            return String(str || '').replace(/\D/g, '').slice(0, 11);
        }

        function formatPhoneBr(digits) {
            const d = phoneDigits(digits);
            if (!d.length) {
                return '';
            }
            if (d.length <= 2) {
                return '(' + d;
            }
            if (d.length <= 6) {
                return '(' + d.slice(0, 2) + ') ' + d.slice(2);
            }
            if (d.length <= 10) {
                return '(' + d.slice(0, 2) + ') ' + d.slice(2, 6) + '-' + d.slice(6);
            }

            return '(' + d.slice(0, 2) + ') ' + d.slice(2, 7) + '-' + d.slice(7);
        }

        function formatPhoneBrDisplay(value) {
            return formatPhoneBr(phoneDigits(value));
        }

        phoneInput.addEventListener('input', () => {
            phoneInput.value = formatPhoneBr(phoneInput.value);
        });

        phoneInput.addEventListener('paste', (e) => {
            e.preventDefault();
            const t = (e.clipboardData || window.clipboardData).getData('text') || '';
            phoneInput.value = formatPhoneBr(phoneDigits(t));
        });

        emailInput.addEventListener('blur', () => {
            emailInput.value = emailInput.value.trim().toLowerCase();
        });

        nameInput.addEventListener('blur', () => {
            nameInput.value = nameInput.value.trim();
        });

        function showFlash(type, message) {
            flash.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            if (type === 'success') setTimeout(() => { flash.innerHTML = ''; }, 4000);
        }

        function clearErrors() {
            flash.querySelectorAll('.alert-error').forEach((n) => n.remove());
        }

        function statusBadge(status) {
            const s = (status || '').toLowerCase();
            const map = {
                pending: ['Pendente', 'badge-pending'],
                processing: ['Calculando', 'badge-pending'],
                active: ['Ativo', 'badge-done'],
                failed: ['Falhou', 'badge-fail'],
            };
            const [label, cls] = map[s] || [status || '—', 'badge-pending'];
            return `<span class="badge ${cls}">${label}</span>`;
        }

        async function loadContacts() {
            clearErrors();
            disconnectScoreBroadcast();
            tableWrap.innerHTML = '<p class="empty">Um momento…</p>';
            try {
                const res = await fetch(API + '?per_page=50', {
                    headers: { Accept: 'application/json' },
                });
                const text = await res.text();
                let json;
                try {
                    json = JSON.parse(text);
                } catch {
                    throw new Error('Algo deu errado ao falar com o servidor.');
                }
                if (!res.ok) throw new Error(json.message || 'Não consegui carregar a lista.');

                const items = json.data || [];
                const meta = json.meta || {};
                const total = meta.total ?? items.length;
                const pg = meta.page ?? 1;
                const palavra = total === 1 ? 'contato' : 'contatos';
                listMeta.textContent = `${total} ${palavra} · página ${pg}`;

                if (items.length === 0) {
                    tableWrap.innerHTML = '<p class="empty">Nenhum contato ainda. Crie o primeiro no formulário acima.</p>';
                    disconnectScoreBroadcast();
                    return;
                }

                const rows = items.map((c) => `
                    <tr data-id="${c.id}">
                        <td class="name-cell">${escapeHtml(c.name)}</td>
                        <td>${escapeHtml(c.email)}</td>
                        <td>${escapeHtml(formatPhoneBrDisplay(c.phone))}</td>
                        <td>${c.score != null ? escapeHtml(String(c.score)) : '—'}</td>
                        <td>${statusBadge(c.status)}</td>
                        <td class="col-actions">
                            <button type="button" class="btn btn-muted btn-sm" data-action="score" data-id="${c.id}">Pontuar</button>
                            <button type="button" class="btn btn-ghost btn-sm" data-action="edit" data-id="${c.id}">Editar</button>
                            <button type="button" class="btn btn-danger btn-sm" data-action="del" data-id="${c.id}">Excluir</button>
                        </td>
                    </tr>
                `).join('');

                tableWrap.innerHTML = `
                    <div class="table-scroll">
                    <table>
                        <thead><tr>
                            <th>Nome</th><th>E-mail</th><th>Telefone</th><th>Pontuação</th><th>Status</th><th class="col-actions">Ações</th>
                        </tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                    </div>`;

                tableWrap.querySelectorAll('button[data-action]').forEach((btn) => {
                    btn.addEventListener('click', onTableAction);
                });

                connectScoreBroadcast(items.map((c) => Number(c.id)));
            } catch (e) {
                disconnectScoreBroadcast();
                tableWrap.innerHTML = `<p class="empty">${escapeHtml(e.message)}</p>`;
            }
        }

        async function onTableAction(ev) {
            const btn = ev.currentTarget;
            const id = btn.getAttribute('data-id');
            const action = btn.getAttribute('data-action');
            clearErrors();

            if (action === 'del') {
                const row = btn.closest('tr');
                const nameCell = row ? row.querySelector('.name-cell') : null;
                const nome = nameCell ? nameCell.textContent.trim() : 'este contato';
                const ok = await openConfirmModal({
                    title: 'Excluir contato',
                    subtitle: '',
                    bodyHtml: `Excluir <strong>${escapeHtml(nome)}</strong>?`,
                    confirmLabel: 'Excluir',
                    danger: true,
                    icon: '🗑',
                });
                if (!ok) return;
                const res = await fetch(`${API}/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
                if (res.status === 204) {
                    showFlash('success', 'Contato removido.');
                    loadContacts();
                } else {
                    const j = await res.json().catch(() => ({}));
                    showFlash('error', j.message || 'Não deu para excluir.');
                }
                return;
            }

            if (action === 'score') {
                const res = await fetch(`${API}/${id}/process-score`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                });
                const j = await res.json().catch(() => ({}));
                if (res.ok) {
                    showFlash('success', j.message || 'Pontuação na fila.');
                    loadContacts();
                } else {
                    showFlash('error', j.message || 'Não deu para enfileirar a pontuação.');
                }
                return;
            }

            if (action === 'edit') {
                const res = await fetch(`${API}/${id}`, { headers: { 'Accept': 'application/json' } });
                const j = await res.json();
                if (!res.ok) {
                    showFlash('error', j.message || 'Contato não encontrado.');
                    return;
                }
                const c = j.data || j;
                editId.value = String(c.id);
                el('name').value = (c.name || '').trim();
                el('email').value = (c.email || '').trim().toLowerCase();
                phoneInput.value = formatPhoneBrDisplay(c.phone || '');
                formTitle.textContent = 'Editar contato';
                btnCancel.hidden = false;
                el('name').focus();
            }
        }

        function resetForm() {
            editId.value = '';
            form.reset();
            formTitle.textContent = 'Novo contato';
            btnCancel.hidden = true;
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearErrors();
            const payload = {
                name: el('name').value.trim(),
                email: el('email').value.trim().toLowerCase(),
                phone: phoneDigits(phoneInput.value),
            };
            const id = editId.value;
            const url = id ? `${API}/${id}` : API;
            const method = id ? 'PUT' : 'POST';

            const res = await fetch(url, {
                method,
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });
            const j = await res.json().catch(() => ({}));

            if (res.ok) {
                showFlash('success', id ? 'Alterações salvas.' : 'Contato adicionado.');
                resetForm();
                loadContacts();
                return;
            }

            if (j.errors) {
                const lines = Object.entries(j.errors).map(([k, v]) => {
                    const label = CAMPOS_PT[k] || k;
                    const msg = Array.isArray(v) ? v.join(', ') : v;
                    return `${label}: ${msg}`;
                }).join('<br>');
                flash.insertAdjacentHTML('beforeend', `<div class="alert alert-error">${lines}</div>`);
            } else {
                showFlash('error', j.message || 'Revise os campos e tente de novo.');
            }
        });

        btnCancel.addEventListener('click', resetForm);
        el('btn-refresh').addEventListener('click', loadContacts);

        loadContacts();
    </script>
</body>
</html>
