@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan: #{{ $order->invoice_code }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Item Produk</h3>
                        @foreach($order->items as $item)
                            <div class="flex gap-4 mb-4 items-center">
                                <img src="{{ $item->product->cover_url ?? asset('images/default.png') }}" class="w-16 h-16 object-cover rounded bg-gray-100">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</p>
                                </div>
                                <div class="font-bold text-gray-700">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="border-t pt-4 mt-4 space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Total Harga</span>
                                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Ongkir</span>
                                <span>Rp {{ number_format($order->shipping_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold text-blue-600">
                                <span>Grand Total</span>
                                <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Informasi Pengiriman</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Penerima</p>
                                <p class="font-bold">{{ $order->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Kurir</p>
                                <p class="font-bold uppercase">{{ $order->courier }} - {{ $order->service }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500">Alamat</p>
                                <p class="text-gray-800">{{ $order->shipping_address }}</p>
                            </div>
                            @if($order->resi_number)
                            <div class="col-span-2 bg-green-50 p-3 rounded border border-green-200">
                                <p class="text-sm text-green-600 font-bold">Nomor Resi</p>
                                <p class="text-lg font-mono tracking-widest text-green-800">{{ $order->resi_number }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Bukti Pembayaran</h3>
                        
                        @if($order->payment_proof)
                            <div class="mb-4">
                                <a href="{{ $order->payment_proof_url }}" target="_blank">
                                    <img src="{{ $order->payment_proof_url }}" alt="Bukti Transfer" class="w-full rounded border hover:opacity-75 transition cursor-zoom-in">
                                </a>
                                <p class="text-xs text-center text-gray-500 mt-2">Klik gambar untuk memperbesar</p>
                            </div>
                        @else
                            <div class="bg-yellow-50 p-4 rounded text-center text-yellow-700 border border-yellow-200">
                                <span class="block text-2xl mb-2">⏳</span>
                                Pembeli belum mengupload bukti pembayaran.
                            </div>
                        @endif
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-blue-500">
                        <h3 class="text-lg font-bold mb-4">Update Status</h3>
                        
                        <form action="{{ route('orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pesanan</label>
                                <select name="status" id="status-select" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="pending_payment" {{ $order->status == 'pending_payment' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="waiting_confirmation" {{ $order->status == 'waiting_confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                    <option value="processed" {{ $order->status == 'processed' ? 'selected' : '' }}>Diproses (Sedang Dikemas)</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            <div id="resi-input" class="mb-4 {{ $order->status == 'shipped' ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Resi</label>
                                <input type="text" name="resi_number" value="{{ $order->resi_number }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Masukkan No. Resi">
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('status-select').addEventListener('change', function() {
            const resiInput = document.getElementById('resi-input');
            if (this.value === 'shipped') {
                resiInput.classList.remove('hidden');
            } else {
                resiInput.classList.add('hidden');
            }
        });
    </script>
@endsection