<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Carts
        </h2>
    </x-slot>

    {{-- <div class="py-12">
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
                    <form action="{{ route('products.checkout') }}" method="POST">
                        @csrf
                        <button style="background: aqua;" type="submit">Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <div id="table" class="bg-white rounded-lg shadow-md p-6 my-6">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="text-left px-6 py-3 border-b-2 border-gray-300 uppercase font-semibold text-sm">Item</th>
                        <th class="text-left px-6 py-3 border-b-2 border-gray-300 uppercase font-semibold text-sm">Quantity</th>
                        <th class="text-left px-6 py-3 border-b-2 border-gray-300 uppercase font-semibold text-sm">Price Each</th>
                        <th class="text-left px-6 py-3 border-b-2 border-gray-300 uppercase font-semibold text-sm">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtot = 0
                    @endphp
                    @foreach ($userCarts as $item)
                        <tr class="hover:bg-gray-100">
                            <td class="border-t px-6 py-4 flex items-center">
                                <img src="https://images.unsplash.com/photo-1601479604588-68d9e6d386b5?ixid=MXwxMjA3fDB8MHxzZWFyY2h8MXx8Y2FuZGxlc3xlbnwwfHwwfA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="" class="w-12 h-12 rounded-full">
                                <div class="pl-4">
                                    {{ $item->product->name }}
                                </div>
                            </td>
                            <td class="border-t px-6 py-4">{{ $item->qty }}</td>
                            <td class="border-t px-6 py-4 text-gray-700">
                                {{ number_format($item->price, 2) }}
                            </td>
                            <td class="border-t px-6 py-4 font-bold text-gray-900">
                                {{ number_format($item->price * $item->qty, 2) }}
                            </td>
                            @php
                                $subtot += $item->price * $item->qty
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-between items-center my-6">
        <div class="text-gray-500">
            {{-- Add any additional info or links if necessary --}}
        </div>
        <div class="flex flex-col items-end">
            <div class="flex px-6 py-3 bg-gray-100 rounded-lg shadow-sm">
                <div class="px-4 text-lg">Subtotal</div>
                <div class="text-2xl font-bold px-2 text-gray-900">{{ number_format($subtot, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="flex justify-end mt-6 mx-6">
        <form action="{{ route('products.checkout') }}" method="POST">
            @csrf
            <button type="submit"
                class="px-6 py-2 rounded-md bg-green-500 text-white hover:bg-green-600 focus:ring-2 focus:ring-green-600">Checkout</button>
        </form>
    </div>
</x-app-layout>
