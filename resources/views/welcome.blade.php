<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARSIPPP — Live Coding Technical Assessment</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
        }
        .layout { min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: #0f172a;
            color: #f8fafc;
            padding: 18px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .topbar h1 { margin: 0 0 4px 0; font-size: 20px; }
        .subtitle { margin: 0; font-size: 13px; color: #94a3b8; }
        .topbar-right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .user-pill {
            background: #1e293b;
            border: 1px solid #334155;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
        }
        .user-pill code { color: #38bdf8; }
        .main-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            flex: 1;
        }
        .sidebar {
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .sidebar-title {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            padding: 4px 10px 8px;
            letter-spacing: 0.06em;
        }
        .tab-btn {
            text-align: left;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
            transition: all 0.15s;
        }
        .tab-btn:hover { background: #f8fafc; }
        .tab-btn.active {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
            font-weight: 600;
        }
        .content-panel { padding: 24px 32px; max-width: 960px; }
        .file-target-bar {
            background: #e2e8f0;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .file-target-bar code {
            font-weight: 600;
            color: #0f172a;
            background: #ffffff;
            padding: 3px 8px;
            border-radius: 4px;
        }
        .question-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 22px 24px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        .question-section h2 { margin-top: 0; font-size: 18px; }
        .question-section p { line-height: 1.6; color: #334155; font-size: 14.5px; }
        .btn-group { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 16px; }
        .btn-primary, .btn-danger, .btn-success, .btn-outline, .btn-outline-sm {
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13.5px;
            cursor: pointer;
            border: none;
            transition: opacity 0.15s;
        }
        .btn-primary:disabled, .btn-danger:disabled, .btn-success:disabled, .btn-outline:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-success { background: #059669; color: #fff; }
        .btn-outline { background: transparent; border: 1px solid #475569; color: #f8fafc; }
        .btn-outline-sm { background: #fff; border: 1px solid #cbd5e1; color: #334155; padding: 6px 10px; font-size: 12px; }
        .card-component {
            margin-top: 16px;
            padding: 18px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }
        .input-row { display: flex; gap: 10px; margin-bottom: 14px; }
        .text-input {
            flex: 1;
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }
        .list-label { font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; }
        .recipient-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 6px; }
        .recipient-item {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }
        .badge-num {
            background: #dbeafe;
            color: #1e40af;
            font-weight: 700;
            font-size: 12px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .surat-preview-box {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 12px 14px;
            border-radius: 6px;
            margin-bottom: 14px;
            font-size: 13.5px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .counter-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fef9c3;
            border: 1px solid #fde047;
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 14px;
        }
        .status-banner {
            margin-top: 12px;
            padding: 10px 12px;
            background: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            border-radius: 6px;
            font-size: 13.5px;
        }
        .result-box {
            margin-top: 20px;
            border-radius: 8px;
            padding: 16px 18px;
            border: 2px solid;
        }
        .result-pass { background: #f0fdf4; border-color: #22c55e; }
        .result-fail { background: #fef2f2; border-color: #ef4444; }
        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            font-size: 14.5px;
        }
        .status-badge {
            background: #0f172a;
            color: #fff;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-family: monospace;
        }
        .json-preview {
            background: #0f172a;
            color: #e2e8f0;
            padding: 12px;
            border-radius: 6px;
            font-size: 12.5px;
            overflow-x: auto;
            max-height: 300px;
            margin: 0;
        }
        .oral-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 12px;
        }
        .oral-card h3 { margin: 0 0 6px 0; font-size: 15px; color: #1e293b; }
        .oral-list { margin: 8px 0 0 0; padding-left: 20px; line-height: 1.7; font-size: 14px; }
    </style>
</head>
<body>
    <div id="app">Memuat Dashboard Live Coding...</div>
    <script src="/playground/bundle.js"></script>
</body>
</html>
