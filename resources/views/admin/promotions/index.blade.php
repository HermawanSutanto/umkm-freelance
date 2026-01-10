@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Permintaan Promosi</h1>
            <p class="text-sm text-gray-500">Verifikasi pembayaran dari Mitra untuk highlight produk.</p>
        </div>
        <div class="bg-yellow-50 text-yellow-700 px-4 py-2 rounded-lg text-sm font-bold border border-yellow-200">
            Pending: {{ $requests->count() }} Permintaan
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Mitra & Produk</th>
                        <th class="px-6 py-4">Paket</th>
                        <th class="px-6 py-4">Bukti Bayar</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $req->created_at->format('d M Y') }}<br>
                            <span class="text-xs">{{ $req->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900 text-sm">{{ $req->user->name }}</p>
                            <p class="text-xs text-indigo-600">{{ $req->product->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($req->plan == 'gold')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold border border-yellow-200">GOLD</span>
                            @elseif($req->plan == 'silver')
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-bold border border-gray-200">SILVER</span>
                            @else
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-bold border border-orange-200">BRONZE</span>
                            @endif
                            <div class="text-xs text-gray-500 mt-1">Rp {{ number_format($req->price_paid, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ Storage::url($req->payment_proof) }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:underline">
                                <i class="material-icons text-sm">visibility</i> Lihat Bukti
                            </a>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Form Reject --}}
                                <form action="{{ route('admin.promotions.reject', $req->id) }}" method="POST" onsubmit="return confirm('Tolak permintaan ini?');">
                                    @csrf
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Tolak">
                                        <i class="material-icons text-sm">close</i>
                                    </button>
                                </form>

                                {{-- Form Approve --}}
                                <form action="{{ route('admin.promotions.approve', $req->id) }}" method="POST" onsubmit="return confirm('Setujui dan aktifkan highlight?');">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-1 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-bold shadow-sm">
                                        <i class="material-icons text-sm">check</i> Approve
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="material-icons text-4xl text-gray-300 mb-2">inbox</i>
                                <p>Tidak ada permintaan promosi baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection