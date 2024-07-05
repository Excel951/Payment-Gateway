{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                    {{-- <p class="text-sm mb-4">Here’s what’s happening with your store today:</p> --}}
                </div>
            </div>
{{--             
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-xl font-semibold mb-2">Total Sales</h4>
                    <p class="text-2xl font-bold text-green-500">$10,000</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-xl font-semibold mb-2">New Orders</h4>
                    <p class="text-2xl font-bold text-blue-500">120</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-xl font-semibold mb-2">Pending Shipments</h4>
                    <p class="text-2xl font-bold text-yellow-500">35</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h4 class="text-xl font-semibold mb-4">Recent Orders</h4>
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Order ID</th>
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-left">Total</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">#12345</td>
                            <td class="border px-4 py-2">John Doe</td>
                            <td class="border px-4 py-2">$120.00</td>
                            <td class="border px-4 py-2">Pending</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">#12346</td>
                            <td class="border px-4 py-2">Jane Smith</td>
                            <td class="border px-4 py-2">$200.00</td>
                            <td class="border px-4 py-2">Completed</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div> --}}
        </div>
    </div>
</x-app-layout>
