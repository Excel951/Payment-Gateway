<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kasir') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 mt-4 pb-24">
        <form action="{{ route('casheer.index') }}" method="get" id="sortForm" class="flex justify-between mb-6">
            @csrf
            <div class="w-full max-w-sm">
                <select name="sort" id="sortProducts"
                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                    onchange="this.form.submit()">
                    <option value="default">Sort by</option>
                    <option value="price-Asc" {{ request('sort') == 'price-Asc' ? 'selected' : '' }}>Price: Low to High
                    </option>
                    <option value="price-Desc" {{ request('sort') == 'price-Desc' ? 'selected' : '' }}>Price: High to
                        Low</option>
                    <option value="name-Asc" {{ request('sort') == 'name-Asc' ? 'selected' : '' }}>Name: A - Z</option>
                    <option value="name-Desc" {{ request('sort') == 'name-Desc' ? 'selected' : '' }}>Name: Z - A
                    </option>
                </select>
            </div>
            <div class="ml-4 flex items-center">
                <input name="search" id="search"
                    class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 pl-10 ring-1 ring-slate-200 shadow-sm"
                    type="text" aria-label="Search Products" placeholder="Search Products...">
                <button type="submit"
                    class="ml-2 px-4 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600 focus:ring-2 focus:ring-blue-600">Search</button>
            </div>
        </form>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $item)
                <div
                    class="product-card bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out">
                    <img src="path/to/image.png" class="w-full h-48 object-cover" alt="Product Image">
                    <form action="{{route('invoice.create')}}" class="p-4">
                        @csrf
                        <h5 class="text-xl font-semibold mb-2 invisible">{{ $item->name }}</h5>
                        <p class="text-gray-600 mb-4">{{ $item->description }}</p>
                        <div class="flex items-center justify-between">
                            <p class="text-green-500 font-bold">{{ $item->price }}</p>
                            <button type="submit" text-blue-500 hover:text-blue-600">Choose product</button>
                            <input type="hidden" name="name" value="{{ $item->name }}">
                            <input type="hidden" name="price" value="{{ $item->price }}">
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
