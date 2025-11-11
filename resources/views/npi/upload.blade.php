<x-guest-layout>
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-4">Bulk NPI Upload</h2>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-2 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif
<script>
    $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  }
});

</script>
        {{-- ✅ Bulk Upload Form --}}
        <form action="{{ route('npi.bulk') }}" method="POST" enctype="multipart/form-data" class="mb-6 border p-4 rounded">
            {{-- ✅ This is critical for CSRF protection --}}
            @csrf

            {{-- ✅ Optional: hidden CSRF fallback for guest layout --}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <h3 class="font-semibold mb-2">Upload NPI Numbers</h3>

            <textarea name="npi_bulk"
                placeholder="Enter multiple NPI numbers (comma, space, or newline separated)"
                class="border rounded p-2 w-full mb-2"
                rows="4"></textarea>

            <div class="flex items-center gap-2">
                <input type="file" name="file" class="border p-1">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Import</button>
            </div>
        </form>

        {{-- ✅ Display Table --}}
        <h3 class="font-semibold mb-3">Stored NPI Numbers</h3>
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">#</th>
                    <th class="border px-3 py-2">NPI Number</th>
                    <th class="border px-3 py-2">Added On</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $rec)
                    <tr>
                        <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-3 py-2">{{ $rec->npi_number }}</td>
                        <td class="border px-3 py-2">{{ $rec->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center p-3">No NPI numbers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ✅ Add this meta tag for CSRF if guest layout lacks it --}}
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
</x-guest-layout>
