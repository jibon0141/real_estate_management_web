<script src="{{ asset('assets/backend_assets/js/tailwind/tailwind.js') }}"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('assets/backend_assets/css/datatable/datatables-1.13.6.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<style>
.select2-container { width: 100% !important; }
.select2-selection--single { height: 44px !important; border: 1px solid #d1d5db !important; border-radius: 0.75rem !important; background-color: #f9fafb !important; }
.select2-selection__rendered { line-height: 44px !important; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 44px !important; }
</style>

<link rel="stylesheet" href="{{ asset('assets/backend_assets/css/admin.css') }}">

@yield('app_styles')
