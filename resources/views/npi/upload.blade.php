<x-guest-layout>
<script src="https://cdn.tailwindcss.com"></script>

<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Bulk NPI Upload</h2>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-2 rounded mb-4">{{ $errors->first() }}</div>
    @endif

    {{-- Bulk Upload Form --}}
    <form action="{{ route('npi.upload') }}" method="POST" enctype="multipart/form-data" class="mb-6 border p-4 rounded">
        @csrf
        <textarea name="npi_bulk"
            placeholder="Enter multiple NPI numbers (comma, space, or newline separated)"
            class="border rounded p-2 w-full mb-2"
            rows="4"></textarea>

        <div class="flex items-center gap-2">
            <input type="file" name="file" class="border p-1">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Import</button>
        </div>
    </form>

    {{-- NPI Numbers Table --}}
    <h3 class="font-semibold mb-3">Stored NPI Numbers</h3>
    <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2">#</th>
                <th class="border px-3 py-2">NPI Number</th>
                <th class="border px-3 py-2">Added On</th>
                <th class="border px-3 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $index => $rec)
                <tr id="npi-row-{{ $rec->id }}" class="{{ $rec->used ? 'bg-gray-200' : '' }}">
                    <td class="border px-3 py-2">{{ $records->firstItem() + $index }}</td>
                    <td class="border px-3 py-2">{{ $rec->npi_number }}</td>
                    <td class="border px-3 py-2">{{ $rec->created_at->format('Y-m-d H:i') }}</td>
                    <td class="border px-3 py-2">
                        <button 
                            class="copy-btn px-2 py-1 rounded text-white {{ $rec->used ? 'bg-gray-500 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600' }}" 
                            data-id="{{ $rec->id }}"
                            data-npi="{{ $rec->npi_number }}"
                            {{ $rec->used ? 'disabled' : '' }}>
                            {{ $rec->used ? 'Used' : 'Copy' }}
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center p-3">No NPI numbers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $records->links() }}
    </div>
</div>

{{-- CSRF Meta Tag --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Copy + AJAX Script --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.copy-btn');

    buttons.forEach(btn => {
        btn.addEventListener('click', async () => {
            const npi = btn.dataset.npi;
            const id = btn.dataset.id;

            if(btn.disabled) return; // already used

            try {
                // Copy to clipboard
                await navigator.clipboard.writeText(npi);

                // Update button UI
                btn.textContent = 'Copied!';
                btn.classList.remove('bg-blue-500','hover:bg-blue-600');
                btn.classList.add('bg-green-500');

                // AJAX mark as used
                fetch("{{ route('npi.markUsed') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        btn.textContent = 'Used';
                        btn.classList.remove('bg-green-500');
                        btn.classList.add('bg-gray-500','cursor-not-allowed');
                        btn.disabled = true;
                        document.getElementById(`npi-row-${id}`).classList.add('bg-gray-200');
                    }
                });

            } catch (err) {
                console.error('Copy failed', err);
            }
        });
    });
});
</script>
</x-guest-layout>
