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

        body {
            background-color: #F3F8FC;
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            background: #2D6BA0;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 24px 0;
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 16px 24px;
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

        /* MAIN AREA */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-brand img {
            width: 52px;
            height: 52px;
            object-fit: contain;
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
        }

        /* ==== KOMPONEN UMUM UNTUK HALAMAN CRUD (Siswa, Kandidat, dll) ==== */

        .btn-back {
            display: inline-block;
            color: #2D6BA0;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .btn-back:hover {
            text-decoration: underline;
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
        }

        .btn-primary {
            background: linear-gradient(90deg, #00B4DB, #0083B0);
            color: #fff;
        }

        .btn-danger {
            background: #FEE2E2;
            color: #DC2626;
        }

        .btn-edit {
            background: #F0F7FF;
            color: #2D6BA0;
            padding: 6px 12px;
        }

        .btn-delete {
            background: #FEE2E2;
            color: #DC2626;
            padding: 6px 12px;
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
        }

        .filter-form input:focus,
        .filter-form select:focus {
            border-color: #38BDF8;
            background: #fff;
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
        }

        .pagination-wrapper nav a:hover {
            background: #F0F7FF;
        }

        /* ==== KOMPONEN FORM (Tambah/Edit) ==== */

        .form-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            padding: 24px 28px;
            max-width: 720px;
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

        .form-row {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 11px;
            font-weight: 700;
            color: #64748B;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group select {
            padding: 11px 14px;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            background: #F8FAFC;
            font-size: 13px;
            color: #334155;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #38BDF8;
            background: #fff;
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
        }
    </style>
</head>
<body>

    @include('admin.partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div class="topbar-brand">
                <img src="{{ asset('image/logo-osis.png') }}" alt="Logo">
                <div>
                    <div class="title">OSIS SMK Pertiwi Kuningan</div>
                    <div class="subtitle">Panel Admin E-Voting</div>
                </div>
            </div>

            <div class="topbar-user">
                <div class="info">
                    <div class="name">Admin OSIS</div>
                    <div class="role">Administrator</div>
                </div>
                <div class="avatar">A</div>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

</body>
</html>