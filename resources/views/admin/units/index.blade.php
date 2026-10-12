@extends('layouts.admin')

@section('title', 'Kelola Unit - Tanjung Api')
@section('header_title', 'Kelola Unit')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div class="space-y-1">
            <h3 class="font-extrabold text-xl text-slate-900 tracking-tight">Daftar Unit Penginapan</h3>
            <p class="text-sm text-slate-500">Kelola semua jenis akomodasi villa dan tenda camping Pantai Tanjung Api.</p>
        </div>
        <a href="{{ route('admin.units.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-emerald-600/10 hover:shadow-emerald-600/25 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Unit
        </a>
    </div>

    <!-- Units Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                        <th class="p-6">Foto</th>
                        <th class="p-6">Nama Unit</th>
                        <th class="p-6">Tipe</th>
                        <th class="p-6">Jumlah Unit</th>
                        <th class="p-6">Alamat</th>
                        <th class="p-6">Fasilitas</th>
                        <th class="p-6">Harga / Malam</th>
                        <th class="p-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                    @forelse($units as $unit)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-6">
                                <div class="w-20 h-14 bg-slate-100 rounded-xl overflow-hidden border border-slate-200 flex-shrink-0">
                                    @if($unit->foto)
                                        <img src="{{ asset('images/' . $unit->foto) }}" class="w-full h-full object-cover" alt="{{ $unit->nama_unit }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="p-6 text-slate-950 font-bold">{{ $unit->nama_unit }}</td>
                            <td class="p-6">
                                @php
                                    $tLower = strtolower($unit->tipe_unit);
                                    $badgeColor = match($tLower) {
                                        'villa'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'tenda'    => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'cottage'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'glamping' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        default    => 'bg-teal-50 text-teal-700 border-teal-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border uppercase {{ $badgeColor }}">
                                    {{ $unit->tipe_unit }}
                                </span>
                            </td>
                            <td class="p-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $unit->jumlah_unit }} unit
                                </span>
                            </td>
                            <td class="p-6 text-slate-500 font-medium max-w-xs truncate" title="{{ $unit->alamat }}">{{ $unit->alamat }}</td>
                            <td class="p-6 text-slate-500 font-medium max-w-xs truncate" title="{{ $unit->fasilitas }}">{{ $unit->fasilitas }}</td>
                            <td class="p-6 text-emerald-600 font-extrabold text-base">
                                Rp {{ number_format($unit->harga, 0, ',', '.') }}
                            </td>
                            <td class="p-6">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.units.edit', $unit->id_unit) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 hover:bg-amber-100 border border-amber-200 hover:border-amber-300 rounded-xl text-xs font-bold text-amber-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.units.destroy', $unit->id_unit) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit ini? Semua reservasi terkait mungkin akan terpengaruh.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-red-50 hover:bg-red-100 border border-red-200 hover:border-red-300 rounded-xl text-xs font-bold text-red-700 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">Belum ada data unit penginapan. Silakan tambahkan unit baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
