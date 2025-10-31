<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6">All Products</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow p-4">
                    <img src="{{ $product->image }}" class="w-full h-40 object-cover rounded">
                    <h3 class="text-lg font-semibold mt-2">{{ $product->name }}</h3>
                    <p class="text-gray-600">${{ $product->price }}</p>
                    <a href="{{ route('cart.add', $product) }}"
                       class="inline-block bg-blue-500 text-white px-4 py-2 rounded mt-3">
                       Add to Cart
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
