@php
    $adminProfil = \App\Models\Admin::find(session('admin_id'));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - E-Voting OSIS SMK Pertiwi Kuningan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
        }

        body {
            background-color: #F3F8FC;
            display: flex;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
        }

        .sidebar {
            width: 240px;
            background: #2D6BA0;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 24px 0;
            flex-shrink: 0;
            height: 100vh;
            height: 100dvh;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 16px 24px;
            position: relative;
        }

        .sidebar-brand img {
            width: 72px;
            height: 72px;
            object-fit: contain;
        }

        .sidebar-brand .name {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.3;
        }

        .sidebar-brand .name span {
            display: block;
            font-weight: 400;
            font-size: 13px;
            opacity: 0.8;
        }

        /* Tombol tutup menu (hanya muncul di HP, lihat media query di bawah) */
        .sidebar-close {
            display: none;
            position: absolute;
            top: -8px;
            right: 12px;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: none;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }

        .sidebar-close:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Lapisan gelap di belakang sidebar saat menu HP terbuka */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 940;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .sidebar-menu {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 8px 12px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 8px;
            color: #E2ECF7;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.15s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .sidebar-menu a i {
            width: 16px;
            text-align: center;
        }

        .sidebar-logout {
            padding: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-logout a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 8px;
            color: #FCD9C9;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }

        .sidebar-logout a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            gap: 10px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        /* Tombol hamburger (hanya muncul di HP) */
        .btn-menu {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            background: #F0F7FF;
            color: #2D6BA0;
            font-size: 18px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.2s ease;
        }

        .btn-menu:hover {
            background: #E0F0FF;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .topbar-brand img {
            width: 52px;
            height: 52px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .topbar-brand .title {
            font-size: 17px;
            font-weight: 700;
            color: #14406B;
        }

        .topbar-brand .subtitle {
            font-size: 13px;
            color: #64748B;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border-radius: 10px;
            transition: background 0.2s ease;
            flex-shrink: 0;
        }

        .topbar-user:hover {
            background: #F0F9FF;
        }

        .topbar-user .info {
            text-align: right;
        }

        .topbar-user .info .name {
            font-size: 13px;
            font-weight: 600;
            color: #1E293B;
        }

        .topbar-user .info .role {
            font-size: 11px;
            color: #94A3B8;
        }

        .topbar-user .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2D6BA0;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .content {
            padding: 28px;
            flex: 1;
            min-width: 0;
            overflow-y: auto;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            color: #2D6BA0;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 18px;
            border-radius: 30px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, border-color 0.2s ease;
        }

        .btn-back i {
            transition: transform 0.2s ease;
        }

        .btn-back:hover {
            background: #F0F9FF;
            border-color: #BAE6FD;
            box-shadow: 0 6px 16px rgba(45, 107, 160, 0.15);
            transform: translateX(-4px);
        }

        .btn-back:hover i {
            transform: translateX(-3px);
        }

        .header-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .header-action h2 {
            font-size: 20px;
            color: #14406B;
        }

        .header-action p {
            font-size: 13px;
            color: #64748B;
            margin-top: 2px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-primary,
        .btn-danger,
        .btn-edit,
        .btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(90deg, #00B4DB, #0083B0);
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 131, 176, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 131, 176, 0.35);
        }

        .btn-primary:active {
            transform: translateY(0) scale(0.97);
        }

        .btn-danger {
            background: #FEE2E2;
            color: #DC2626;
        }

        .btn-danger:hover {
            filter: brightness(0.96);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(220, 38, 38, 0.15);
        }

        .btn-edit {
            background: #F0F7FF;
            color: #2D6BA0;
            padding: 6px 12px;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(45, 107, 160, 0.15);
        }

        .btn-delete {
            background: #FEE2E2;
            color: #DC2626;
            padding: 6px 12px;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(220, 38, 38, 0.15);
        }

        .btn-primary:active,
        .btn-danger:active,
        .btn-edit:active,
        .btn-delete:active {
            transform: translateY(0) scale(0.96);
        }

        .filter-form {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .filter-form input,
        .filter-form select {
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            background: #F8FAFC;
            font-size: 13px;
            color: #334155;
            outline: none;
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .filter-form input:focus,
        .filter-form select:focus {
            border-color: #38BDF8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        .filter-form input {
            flex: 1;
            min-width: 200px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        }

        .table thead {
            background: #F8FAFC;
        }

        .table th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748B;
            padding: 12px 16px;
            border-bottom: 1px solid #E2E8F0;
        }

        .table td {
            padding: 12px 16px;
            font-size: 13px;
            color: #334155;
            border-bottom: 1px solid #F1F5F9;
        }

        .table tbody tr {
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background: #F8FAFC;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 16px;
            font-size: 12px;
            color: #64748B;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagination-wrapper nav ul {
            display: flex;
            list-style: none;
            gap: 4px;
            flex-wrap: wrap;
        }

        .pagination-wrapper nav a,
        .pagination-wrapper nav span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 30px;
            padding: 0 8px;
            border-radius: 8px;
            font-size: 12px;
            text-decoration: none;
            color: #334155;
            transition: background 0.15s ease;
        }

        .pagination-wrapper nav a:hover {
            background: #F0F7FF;
        }

        .form-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            padding: 32px 30px;
            max-width: 480px;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 18px 44px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .form-card h2 {
            font-size: 18px;
            font-weight: 700;
            color: #14406B;
            margin-bottom: 4px;
        }

        .form-subtitle {
            font-size: 12px;
            color: #64748B;
            margin-bottom: 22px;
        }

        .form-error-box {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .form-error-box ul {
            margin: 6px 0 0 18px;
        }

        .field-group {
            margin-bottom: 16px;
        }

        .field-group label {
            font-size: 11px;
            font-weight: 700;
            color: #64748B;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 6px;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .input-icon-wrapper input,
        .input-icon-wrapper select {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            background: #F8FAFC;
            font-size: 13px;
            color: #334155;
            outline: none;
            appearance: none;
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .input-icon-wrapper select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2394A3B8' d='M6 8 0 0h12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
        }

        .input-icon-wrapper input::placeholder {
            color: #94A3B8;
        }

        .input-icon-wrapper input:focus,
        .input-icon-wrapper select:focus {
            border-color: #38BDF8;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        .input-icon-wrapper input:focus + i,
        .input-icon-wrapper select:focus + i {
            color: #38BDF8;
        }

        .input-icon-password-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 12px 14px 12px 40px;
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .input-icon-password-wrapper:focus-within {
            border-color: #38BDF8;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        .input-icon-password-wrapper input {
            flex: 1;
            min-width: 0;
            width: auto;
            border: none;
            outline: none;
            padding: 0;
            border-radius: 0;
            background: transparent;
            font-size: 13px;
            color: #334155;
        }

        .input-icon-password-wrapper input::placeholder {
            color: #94A3B8;
        }

        .icon-toggle-password {
            margin-left: auto;
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 0;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .input-icon-password-wrapper .icon-toggle-password i {
            position: static;
            left: auto;
            top: auto;
            transform: none;
            pointer-events: auto;
            color: inherit;
            font-size: inherit;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 8px;
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            padding: 9px 18px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            color: #64748B;
            background: #F1F5F9;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .btn-cancel:hover {
            background: #E2E8F0;
            transform: translateY(-2px);
        }

        .btn-cancel:active {
            transform: translateY(0) scale(0.97);
        }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 999;
            padding: 16px;
        }

        .modal-box {
            background: #fff;
            border-radius: 20px;
            padding: 32px 30px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .modal-icon-warning {
            width: 56px;
            height: 56px;
            background: #FEE2E2;
            color: #DC2626;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin: 0 auto 16px;
        }

        .modal-title-danger {
            color: #DC2626;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .modal-desc {
            font-size: 13px;
            color: #64748B;
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .modal-warning-box {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #B91C1C;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 12px;
            text-align: left;
            margin-bottom: 20px;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-danger-solid {
            background: #DC2626;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-danger-solid:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(220, 38, 38, 0.3);
        }

        .btn-danger-solid:active {
            transform: translateY(0) scale(0.97);
        }

        .btn-cancel-outline {
            background: #fff;
            border: 1px solid #CBD5E1;
            color: #334155;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .btn-cancel-outline:hover {
            background: #F8FAFC;
            transform: translateY(-2px);
        }

        .btn-cancel-outline:active {
            transform: translateY(0) scale(0.97);
        }

        .tab-switch {
            display: flex;
            background: #F1F5F9;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 20px;
            gap: 4px;
        }

        .tab-btn {
            flex: 1;
            text-align: center;
            padding: 9px 0;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            color: #64748B;
            background: transparent;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background 0.2s ease, box-shadow 0.2s ease, color 0.2s ease;
        }

        .tab-btn:hover:not(.active) {
            background: rgba(255, 255, 255, 0.6);
            color: #2D6BA0;
        }

        .tab-btn.active {
            background: #fff;
            color: #2D6BA0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        .dropzone {
            display: block;
            width: 100%;
            border: 2px dashed #BAE6FD;
            border-radius: 14px;
            background: #F0F9FF;
            text-align: center;
            padding: 36px 20px;
            cursor: pointer;
            margin-bottom: 20px;
            transition: border-color 0.2s ease, background 0.2s ease, transform 0.2s ease;
        }

        .dropzone:hover {
            border-color: #38BDF8;
            background: #E0F2FE;
            transform: translateY(-2px);
        }

        .dropzone i {
            font-size: 30px;
            color: #F0B429;
            margin-bottom: 12px;
        }

        .dropzone .dz-title {
            font-size: 13px;
            font-weight: 700;
            color: #14406B;
            margin-bottom: 4px;
        }

        .dropzone .dz-sub {
            font-size: 12px;
            color: #94A3B8;
            margin-bottom: 10px;
        }

        .dropzone a {
            font-size: 12px;
            color: #2D6BA0;
            font-weight: 600;
            text-decoration: underline;
        }

        .dropzone .dz-filename {
            font-size: 12px;
            color: #16A34A;
            font-weight: 600;
            margin-top: 10px;
        }

        .kandidat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(260px, 100%), 1fr));
            gap: 20px;
        }

        .kandidat-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .kandidat-card:hover {
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .kandidat-photo-wrap {
            position: relative;
            aspect-ratio: 4 / 3;
            background: #F1F5F9;
            overflow: hidden;
        }

        .kandidat-photo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .kandidat-card:hover .kandidat-photo-wrap img {
            transform: scale(1.05);
        }

        .kandidat-photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
            font-size: 30px;
        }

        .kandidat-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #1E293B;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            z-index: 2;
        }

        .kandidat-card-body {
            padding: 16px;
        }

        .kandidat-card-body h3 {
            font-size: 15px;
            color: #14406B;
            margin-bottom: 2px;
        }

        .kandidat-wakil {
            font-size: 12px;
            color: #64748B;
            margin-bottom: 8px;
        }

        .kandidat-visi {
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 14px;
            min-height: 36px;
        }

        .kandidat-actions {
            display: flex;
            gap: 8px;
        }

        .kandidat-actions .btn-edit,
        .kandidat-actions .btn-delete {
            flex: 1;
            justify-content: center;
        }

        .modal-box-lg {
            max-width: 560px;
            max-height: 88vh;
            overflow-y: auto;
            text-align: left;
        }

        .modal-form-title {
            font-size: 18px;
            font-weight: 700;
            color: #14406B;
            margin-bottom: 18px;
        }

        .form-row-2 {
            display: flex;
            gap: 12px;
        }

        .form-row-2 .field-group {
            flex: 1;
            min-width: 0;
        }

        .plain-input {
            width: 100%;
            padding: 11px 14px;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            background: #F8FAFC;
            font-size: 13px;
            color: #334155;
            outline: none;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .plain-input:focus {
            border-color: #38BDF8;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        textarea.plain-input {
            resize: vertical;
        }

        .poin-row {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
            align-items: center;
        }

        .poin-row input {
            flex: 1;
        }

        .poin-remove {
            background: #FEE2E2;
            color: #DC2626;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            flex-shrink: 0;
            transition: transform 0.15s ease, background 0.2s ease;
        }

        .poin-remove:hover {
            background: #FECACA;
            transform: scale(1.08);
        }

        .tambah-poin-link {
            display: inline-block;
            font-size: 12px;
            color: #2D6BA0;
            font-weight: 600;
            text-decoration: none;
            margin: 4px 0 16px;
        }

        .foto-preview-img {
            max-width: 100%;
            max-height: 160px;
            border-radius: 10px;
            object-fit: cover;
        }

        .modal-hapus-foto {
            width: 64px;
            height: 64px;
            border-radius: 10px;
            object-fit: cover;
            margin: 0 auto 10px;
            display: block;
        }

        .btn-status-action {
            background: #F3E8FF;
            color: #7C3AED;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-status-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(124, 58, 237, 0.2);
        }

        .status-select-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .status-select-btn {
            flex: 1;
            padding: 10px 0;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            background: #fff;
            color: #334155;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }

        .status-select-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        }

        .status-select-btn[data-status="draft"].selected {
            background: #94A3B8;
            border-color: #94A3B8;
            color: #fff;
        }

        .status-select-btn[data-status="active"].selected {
            background: #22C55E;
            border-color: #22C55E;
            color: #fff;
        }

        .status-select-btn[data-status="inactive"].selected {
            background: #EF4444;
            border-color: #EF4444;
            color: #fff;
        }

        .hasil-stat-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin: 16px 0;
        }

        .partisipasi-track {
            width: 100%;
            height: 8px;
            background: #F1F5F9;
            border-radius: 20px;
            overflow: hidden;
        }

        .partisipasi-fill {
            height: 100%;
            background: linear-gradient(90deg, #22C55E, #16A34A);
            border-radius: 20px;
        }

        .hasil-grid {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 16px;
        }

        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #2D6BA0;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
        }

        .chart-toggle-group {
            display: flex;
            gap: 4px;
            background: #F1F5F9;
            border-radius: 10px;
            padding: 3px;
        }

        .chart-toggle-btn {
            padding: 5px 12px;
            border-radius: 7px;
            border: none;
            background: transparent;
            font-size: 11px;
            font-weight: 700;
            color: #64748B;
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .chart-toggle-btn.selected {
            background: #2D6BA0;
            color: #fff;
        }

        .aksi-lanjutan-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .modal-icon-info {
            width: 56px;
            height: 56px;
            background: #DBEAFE;
            color: #2D6BA0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin: 0 auto 16px;
        }

        /* ==== FOTO PROFIL: tombol X buat menandai "hapus foto" ==== */
        .foto-profil-area {
            position: relative;
            display: inline-block;
        }

        .btn-hapus-foto {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #DC2626;
            color: #fff;
            border: 2px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            transition: transform 0.15s ease, background 0.2s ease;
        }

        .btn-hapus-foto:hover {
            background: #B91C1C;
            transform: scale(1.1);
        }

        /* =====================================================
           RESPONSIF: TABLET & HP
           ===================================================== */

        /* Tablet & HP: sidebar jadi menu geser (hamburger) */
        @media (max-width: 900px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: min(260px, 82vw);
                z-index: 950;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                box-shadow: none;
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: 8px 0 30px rgba(0, 0, 0, 0.25);
            }

            .sidebar-brand img {
                width: 56px;
                height: 56px;
            }

            .sidebar-close {
                display: flex;
            }

            .btn-menu {
                display: inline-flex;
            }

            .topbar {
                padding: 10px 16px;
            }

            .topbar-brand img {
                width: 40px;
                height: 40px;
            }

            .topbar-brand .title {
                font-size: 15px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .topbar-brand .subtitle {
                font-size: 11px;
            }

            .content {
                padding: 18px 16px;
            }

            /* Tabel lebar: bisa digeser ke samping, halaman tidak ikut melebar */
            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }

            .hasil-grid {
                grid-template-columns: 1fr;
            }

            .form-card {
                max-width: 100%;
            }
        }

        /* HP */
        @media (max-width: 600px) {
            .hasil-stat-cards {
                grid-template-columns: 1fr;
            }

            .header-action h2 {
                font-size: 17px;
            }

            .btn-group {
                width: 100%;
            }

            .filter-form input {
                min-width: 0;
                width: 100%;
                flex: 1 1 100%;
            }

            .filter-form select {
                flex: 1 1 100%;
            }

            .form-card {
                padding: 24px 18px;
            }

            .form-card:hover {
                transform: none;
            }

            .modal-box {
                padding: 24px 18px;
                width: 100%;
            }

            .form-row-2 {
                flex-direction: column;
                gap: 0;
            }

            .modal-actions {
                flex-wrap: wrap;
            }

            .pagination-wrapper {
                justify-content: center;
                text-align: center;
            }
        }

        /* HP kecil: topbar cuma tampilkan avatar admin */
        @media (max-width: 480px) {
            .topbar-brand .subtitle {
                display: none;
            }

            .topbar-user .info {
                display: none;
            }

            .topbar-user {
                padding: 4px;
            }

            .topbar-brand .title {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

    <!-- Lapisan gelap di belakang menu HP -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="tutupSidebar()"></div>

    @include('admin.partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div class="topbar-left">
                <button type="button" class="btn-menu" onclick="bukaSidebar()" aria-label="Buka menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="topbar-brand">
                    <img src="{{ asset('image/logo-osis.png') }}" alt="Logo">
                    <div>
                        <div class="title">OSIS SMK Pertiwi Kuningan</div>
                        <div class="subtitle">Panel Admin E-Voting</div>
                    </div>
                </div>
            </div>

            <button type="button" class="topbar-user" onclick="bukaModalProfil()" style="background:none; border:none; cursor:pointer;">
                <div class="info">
                    <div class="name">{{ session('admin_nama') ?: session('admin_username') }}</div>
                    <div class="role">Administrator</div>
                </div>
                @if (session('admin_foto'))
                    <img src="{{ asset('image/' . session('admin_foto')) }}" class="avatar" style="object-fit:cover;" alt="Foto Admin">
                @else
                    <div class="avatar">{{ strtoupper(substr(session('admin_nama') ?: session('admin_username'), 0, 1)) }}</div>
                @endif
            </button>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- MODAL PROFIL ADMIN (tersedia di semua halaman admin) -->
    <div class="modal-overlay" id="modalProfil">
        <div class="modal-box modal-box-lg">
            <h3 class="modal-form-title">Profil Admin</h3>
            <p class="form-subtitle" style="margin-top:-12px;">Kelola informasi akun admin kamu</p>

            @if (session('success_profil'))
                <div class="modal-warning-box" style="background:#F0FDF4; border-color:#BBF7D0; color:#16A34A;">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success_profil') }}</span>
                </div>
            @endif

            @if ($errors->profilForm->any())
                <div class="form-error-box">
                    <strong>Ada kesalahan input:</strong>
                    <ul>
                        @foreach ($errors->profilForm->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="field-group" style="text-align:center;">
                    <div class="foto-profil-area" id="fotoProfilArea">
                        <label for="inputFotoProfil" class="dropzone" id="dropzoneFotoProfil" style="cursor:pointer;">
                            <div id="previewFotoProfilWrapper">
                                @if ($adminProfil && $adminProfil->foto)
                                    <img src="{{ asset('image/' . $adminProfil->foto) }}" class="foto-preview-img" style="border-radius:50%; width:90px; height:90px; object-fit:cover;">
                                @else
                                    <i class="fa-regular fa-user" style="font-size:36px;"></i>
                                    <div class="dz-sub">Klik untuk upload foto profil</div>
                                @endif
                            </div>
                        </label>

                        <button type="button" class="btn-hapus-foto" id="btnHapusFoto"
                            onclick="hapusFotoProfil()"
                            title="Hapus foto profil"
                            style="{{ ($adminProfil && $adminProfil->foto) ? '' : 'display:none;' }}">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <input type="file" name="foto" id="inputFotoProfil" accept="image/*" style="display:none" onchange="previewFotoProfil(this)">
                    <input type="hidden" name="hapus_foto" id="hapusFotoFlag" value="0">
                </div>

                <div class="field-group">
                    <label>Nama Lengkap</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $adminProfil->nama_lengkap ?? '') }}" placeholder="Nama lengkap admin">
                    </div>
                </div>

                <div class="field-group">
                    <label>Username</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-at"></i>
                        <input type="text" name="username" value="{{ old('username', $adminProfil->username ?? '') }}" placeholder="Username login">
                    </div>
                </div>

                <div class="field-group">
                    <label>Password Baru</label>
                    <div class="input-icon-wrapper input-icon-password-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" id="passwordProfil"
                            placeholder="Kosongkan jika tidak diganti" autocomplete="new-password"
                            data-lpignore="true" data-1p-ignore data-form-type="other">
                        <button type="button" class="icon-toggle-password" onclick="togglePasswordField('passwordProfil', 'eyeProfil')">
                            <i class="fa-solid fa-eye" id="eyeProfil"></i>
                        </button>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn-cancel" onclick="tutupModalProfil()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KONFIRMASI LOGOUT (tersedia di semua halaman admin) -->
    <div class="modal-overlay" id="modalLogout">
        <div class="modal-box">
            <div class="modal-icon-info">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
            <h3 style="font-size:16px; font-weight:700; color:#1E293B; margin-bottom:6px;">Konfirmasi Logout</h3>
            <p class="modal-desc">Yakin ingin logout dari panel admin?</p>
            <div class="modal-actions">
                <a href="{{ route('admin.logout') }}" class="btn-primary" style="text-decoration:none;">Ya, Logout</a>
                <button type="button" class="btn-cancel-outline" onclick="tutupModalLogout()">Batal</button>
            </div>
        </div>
    </div>

    <script>
        // ===== Menu samping (hamburger di HP) =====
        function bukaSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('sidebarOverlay').classList.add('active');
        }
        function tutupSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }

        // Tutup menu dengan tombol Esc, dan otomatis tertutup kalau layar dibesarkan
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') tutupSidebar();
        });
        window.addEventListener('resize', function () {
            if (window.innerWidth > 900) tutupSidebar();
        });

        // ===== Modal Logout & Profil =====
        function bukaModalLogout() {
            tutupSidebar();
            document.getElementById('modalLogout').style.display = 'flex';
        }
        function tutupModalLogout() {
            document.getElementById('modalLogout').style.display = 'none';
        }

        function bukaModalProfil() {
            document.getElementById('modalProfil').style.display = 'flex';
        }
        function tutupModalProfil() {
            document.getElementById('modalProfil').style.display = 'none';
        }

        function previewFotoProfil(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('previewFotoProfilWrapper').innerHTML =
                        '<img src="' + e.target.result + '" class="foto-preview-img" style="border-radius:50%; width:90px; height:90px; object-fit:cover;">';
                };
                reader.readAsDataURL(input.files[0]);

                // Foto baru dipilih -> batalkan penanda hapus, tampilkan lagi tombol X
                document.getElementById('hapusFotoFlag').value = '0';
                document.getElementById('btnHapusFoto').style.display = 'flex';
            }
        }

        function hapusFotoProfil() {
            // Kosongkan input file (batalkan foto baru kalau sempat dipilih)
            document.getElementById('inputFotoProfil').value = '';

            // Balikin preview ke placeholder default
            document.getElementById('previewFotoProfilWrapper').innerHTML =
                '<i class="fa-regular fa-user" style="font-size:36px;"></i><div class="dz-sub">Klik untuk upload foto profil</div>';

            // Tandai: foto ini beneran mau dihapus saat "Simpan Perubahan" diklik
            document.getElementById('hapusFotoFlag').value = '1';

            // Sembunyikan tombol X-nya karena sudah tidak ada foto untuk dihapus
            document.getElementById('btnHapusFoto').style.display = 'none';
        }

        function togglePasswordField(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        @if (session('success_profil') || $errors->profilForm->any())
            document.addEventListener('DOMContentLoaded', function () {
                bukaModalProfil();
            });
        @endif
    </script>

</body>
</html>