{{--
    resources/views/layouts/sidebar.blade.php
    Glassmorphism sidebar - frosted glass effect over the gradient background.
--}}
<aside id="sidebar" class="w-64 h-dvh bg-neutral-900 text-white flex flex-col fixed left-0 top-0 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-200">

    {{-- Logo --}}
    <div class="flex items-center gap-2 p-6 text-xl font-bold shrink-0 text-orange-300">
        🛒 <span>Quantum Commerce</span>
    </div>

    {{-- Nav links --}}
    <nav class="flex-1 overflow-y-auto flex flex-col gap-1 px-3">

        <a href="{{ route('profile.show') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('profile.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
            👤 <span>My Profile</span>
        </a>

        {{-- ============ CUSTOMER-ONLY LINKS ============ --}}
        @if (auth()->user()->isCustomer())
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('products.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                🛍️ <span>Shop Products</span>
            </a>
            <a href="{{ route('cart.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('cart.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                🧺 <span>My Cart</span>
            </a>
            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('orders.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                📦 <span>My Orders</span>
            </a>
        @endif

        {{-- ============ VENDOR-ONLY LINKS ============ --}}
        @if (auth()->user()->isVendor())
            @if (auth()->user()->isApproved())
                <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('vendor.dashboard') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                    📊 <span>Vendor Dashboard</span>
                </a>
                <a href="{{ route('vendor.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('vendor.products.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                    🏷️ <span>My Products</span>
                </a>
                <a href="{{ route('vendor.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('vendor.orders.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                    📦 <span>Orders Received</span>
                </a>
                <a href="{{ route('messages.vendor.inbox') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('messages.vendor.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                    💬 <span>Messages</span>
                </a>
                <a href="{{ route('vendor.payouts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('vendor.payouts.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                    💰 <span>Payouts</span>
                </a>
            @else
                <a href="{{ route('approval.pending') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl bg-amber-500/30 hover:bg-amber-500/40 transition">
                    ⏳ <span>Pending Approval</span>
                </a>
            @endif
        @endif

        {{-- ============ RIDER-ONLY LINKS ============ --}}
        @if (auth()->user()->isRider())
            @if (auth()->user()->isApproved())
                <a href="{{ route('rider.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('rider.dashboard') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                    🏠 <span>Dashboard</span>
                </a>
                <a href="{{ route('rider.deliveries.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('rider.deliveries.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                    🏍️ <span>Deliveries</span>
                </a>
                <a href="{{ route('rider.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('rider.history') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                    📜 <span>History</span>
                </a>
            @else
                <a href="{{ route('approval.pending') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl bg-amber-500/30 hover:bg-amber-500/40 transition">
                    ⏳ <span>Pending Approval</span>
                </a>
            @endif
        @endif

        {{-- ============ ADMIN-ONLY LINKS ============ --}}
        @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                📊 <span>Admin Dashboard</span>
            </a>
            <a href="{{ route('admin.approvals') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('admin.approvals') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                ✅ <span>Vendor/Rider Approvals</span>
            </a>
            <a href="{{ route('admin.vendors') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('admin.vendors') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                🏷️ <span>Manage Vendors</span>
            </a>
            <a href="{{ route('admin.riders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('admin.riders') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                🏍️ <span>Manage Riders</span>
            </a>
            <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('admin.roles.index') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                🔐 <span>Roles & Permissions</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('admin.categories.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                📂 <span>Categories</span>
            </a>
            <a href="{{ route('admin.payouts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('admin.payouts.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                💵 <span>Payouts</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition {{ request()->routeIs('admin.reports.*') ? 'bg-white/20 backdrop-blur-sm' : '' }}">
                📈 <span>Reports</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-6 py-3 hover:bg-neutral-800 {{ request()->routeIs('admin.users.index') ? 'bg-orange-600' : '' }}">
                👥 <span>All Users</span>
            </a>
        @endif
    </nav>

    {{-- Bottom section: user info + logout --}}
    <div class="border-t border-white/20 p-4 shrink-0">
        <div class="flex items-center gap-3 mb-3 bg-white/10 rounded-xl p-3 backdrop-blur-sm">
    @if (auth()->user()->profile_photo)
        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-9 h-9 rounded-full object-cover shrink-0">
    @else
        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-400 to-purple-500 text-white flex items-center justify-center font-bold shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    @endif
    <div>
        <p class="font-semibold text-sm">{{ strtoupper(auth()->user()->name) }}</p>
        <p class="text-xs text-orange-300">{{ auth()->user()->roleLabel() }}</p>
    </div>
</div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2 text-sm text-orange-300 hover:text-orange-200 hover:underline">
                🚪 <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
{{-- Mobile menu toggle button - only visible on small screens --}}
<button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full'); document.getElementById('sidebar-overlay').classList.toggle('hidden');"
        class="lg:hidden fixed top-4 left-4 z-50 bg-neutral-900 text-white p-2 rounded-lg shadow-lg">
    ☰
</button>

{{-- Dark overlay behind the sidebar when open on mobile, tapping it closes the menu --}}
<div id="sidebar-overlay"
     onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); this.classList.add('hidden');"
     class="hidden lg:hidden fixed inset-0 bg-black/50 z-30">
</div>