<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Carts
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($message = session()->get('success'))
                    <div class="alert">{{ $message }}</div>
                @endif

                <div class="p-6
                        text-gray-900">
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                        </tr>
                        @foreach ($userCarts as $userCart)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $userCart->product->name }}</td>
                                <td>{{ $userCart->qty }}</td>
                                <td>{{ $userCart->price }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
