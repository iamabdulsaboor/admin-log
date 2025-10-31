<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6">Your Cart</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(count($cart))
            <table class="w-full bg-white shadow rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Product</th>
                        <th class="p-2 text-left">Price</th>
                        <th class="p-2 text-left">Quantity</th>
                        <th class="p-2 text-left">Subtotal</th>
                        <th class="p-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php $total += $item['price'] * $item['quantity']; @endphp
                        <tr class="border-b">
                            <td class="p-2 flex items-center space-x-2">
                                <img src="{{ $item['image'] }}" class="w-12 h-12 rounded object-cover">
                                <span>{{ $item['name'] }}</span>
                            </td>
                            <td class="p-2">${{ $item['price'] }}</td>
                            <td class="p-2">{{ $item['quantity'] }}</td>
                            <td class="p-2">${{ $item['price'] * $item['quantity'] }}</td>
                            <td class="p-2">
                                <a href="{{ route('cart.remove', $id) }}" class="text-red-500">Remove</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 flex justify-between">
                <h3 class="text-xl font-bold">Total: ${{ $total }}</h3>
                <a href="{{ route('cart.clear') }}" class="bg-red-500 text-white px-4 py-2 rounded">Clear Cart</a>
            </div>
        @else
            <p class="text-gray-600">Your cart is empty.</p>
        @endif
    </div>
</x-app-layout>
