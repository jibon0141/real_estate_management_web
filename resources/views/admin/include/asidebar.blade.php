<aside id="sidebar"
       class="sidebar fixed top-0 left-0 h-screen overflow-hidden flex flex-col">

    <div class="sidebar-brand flex items-center gap-3 h-18 px-5 py-4 bg-gradient-to-r from-slate-900 to-slate-800 flex-shrink-0">
        <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
            <span class="text-white font-bold text-lg">T</span>
        </div>
        <div class="sidebar-brand-text min-w-0 flex-1">
            <h1 class="text-white font-bold text-sm leading-tight truncate">Easy IT Solution LTD.</h1>
            <span class="text-white/50 text-[10px]">Administration</span>
        </div>
        <button id="sidebar-close-btn"
                class="md:hidden p-1.5 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav flex-1 overflow-y-auto overflow-x-hidden py-3" id="sidebar-nav">

        <span class="sidebar-section-title">Main</span>

        <a href="{{ url('/admin/dashboard') }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="sidebar-nav-text">Dashboard</span>
        </a>

        <div class="sidebar-dropdown">
            <button class="sidebar-dropdown-btn" onclick="toggleSidebarDropdown(this)">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
                <span class="sidebar-nav-text">User Management</span>
                <svg class="sidebar-dropdown-arrow w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="sidebar-dropdown-menu hidden">
                <a href="{{ route('admin.designation.index') }}">Designation Management</a>
                <a href="{{ route('admin.user.index') }}">User Management</a>
            </div>
        </div>

        <div class="sidebar-dropdown">
            <button class="sidebar-dropdown-btn" onclick="toggleSidebarDropdown(this)">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <span class="sidebar-nav-text">Account Management</span>
                <svg class="sidebar-dropdown-arrow w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="sidebar-dropdown-menu hidden">
                <a href="{{ route('admin.gl-account.index') }}">GL Account</a>
                <a href="{{ route('admin.account.index') }}">Account</a>
                <a href="{{ route('admin.chart-of-account.index') }}">Chart Of Account</a>
                <a href="{{ route('admin.party.index') }}">Party</a>
                <a href="{{ route('admin.debit-voucher.index') }}">Debit</a>
                <a href="{{ route('admin.credit-voucher.index') }}">Credit</a>
            </div>
        </div>

        <div class="sidebar-dropdown">
            <button class="sidebar-dropdown-btn" onclick="toggleSidebarDropdown(this)">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="sidebar-nav-text">Revenue Management</span>
                <svg class="sidebar-dropdown-arrow w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="sidebar-dropdown-menu hidden">
                <a href="{{ route('admin.commission-setting.index') }}">Commission Settings</a>
                <a href="{{ route('admin.set-commission.index') }}">Set Commission</a>
            </div>
        </div>

        <div class="sidebar-dropdown">
            <button class="sidebar-dropdown-btn" onclick="toggleSidebarDropdown(this)">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>
                </svg>
                <span class="sidebar-nav-text">Project Management</span>
                <svg class="sidebar-dropdown-arrow w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div class="sidebar-dropdown-menu hidden">
                <a href="{{ route('admin.project.index') }}">Project Management</a>
                <a href="{{ route('admin.package.index') }}">Package Management</a>
            </div>
        </div>

    </nav>

<script>
function toggleSidebarDropdown(btn) {
    const allBtns = document.querySelectorAll('.sidebar-dropdown-btn');
    allBtns.forEach(function(otherBtn) {
        if (otherBtn !== btn) {
            const otherMenu = otherBtn.nextElementSibling;
            const otherArrow = otherBtn.querySelector('.sidebar-dropdown-arrow');
            if (otherMenu && !otherMenu.classList.contains('hidden')) {
                otherMenu.classList.add('hidden');
                if (otherArrow) otherArrow.classList.remove('rotate-180');
            }
        }
    });
    const menu = btn.nextElementSibling;
    const arrow = btn.querySelector('.sidebar-dropdown-arrow');
    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}
</script>

<style>
.sidebar-dropdown-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    width: 100%;
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    color: #94a3b8;
    transition: all 0.2s;
    background: none;
    border: none;
    cursor: pointer;
}
.sidebar-dropdown-btn:hover {
    color: white;
    background: rgba(255,255,255,0.05);
}
.sidebar-dropdown-menu {
    overflow: hidden;
    transition: max-height 0.3s ease;
}
.sidebar-dropdown-menu a {
    display: flex;
    align-items: center;
    padding: 0.5rem 1.25rem 0.5rem 3.25rem;
    font-size: 0.85rem;
    color: #94a3b8;
    text-decoration: none;
    transition: all 0.2s;
}
.sidebar-dropdown-menu a:hover {
    color: white;
    background: rgba(255,255,255,0.05);
}
.sidebar-dropdown-menu.hidden {
    display: none;
}
.rotate-180 {
    transform: rotate(180deg);
}
.sidebar.collapsed .sidebar-dropdown-btn {
    justify-content: center;
    padding: 0.75rem;
    margin: 0 0.5rem;
    gap: 0;
}
.sidebar.collapsed .sidebar-dropdown-arrow {
    display: none;
}
.sidebar.collapsed .sidebar-dropdown-menu {
    display: none;
}
</style>

    <div class="sidebar-footer-text px-3 py-3 border-t border-slate-700/50 flex-shrink-0">
        <a href="{{ url('/') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs text-slate-500 hover:text-white hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="text-white">Back to Website</span>
        </a>
    </div>
</aside>
