@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pesanan Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex gap-2 overflow-x-auto pb-2">
                @php
                    $statuses = [
                        '' => 'Semua',
                        'pending_payment' => 'Belum Bayar',
                        'waiting_confirmation' => 'Perlu Cek Bukti',
                        'processed' => 'Perlu Dikemas',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Batal'
                    ];
                @endphp
                @foreach($statuses as $key => $label)
                    <a href="{{ route('orders.index', ['status' => $key]) }}" 
                       class="px-4 py-2 rounded-full text-sm font-bold border transition
                       {{ request('status') == $key ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembeli</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-blue-600">
                                    #{{ $order->invoice_code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $order->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->status == 'waiting_confirmation') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'processed') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                                        @elseif($order->status == 'completed') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('orders.show', $order->invoice_code) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada pesanan ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection