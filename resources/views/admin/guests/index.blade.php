@extends('layouts.admin')

@section('title', 'Kelola Tamu - Tanjung Api')
@section('header_title', 'Kelola Tamu')

@section('content')
<div class="space-y-6">
    <!-- Header Info & Action -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="font-extrabold text-xl text-slate-900 tracking-tight">Daftar Akun Tamu / Pelanggan</h3>
            <p class="text-sm text-slate-500 mt-1">Daftar lengkap akun wisatawan yang terdaftar dalam sistem reservasi Pantai Tanjung Api.</p>
        </div>
        @if($guests->count() > 0)
            <div>
                <form action="{{ route('admin.guests.clean-all') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin membersihkan/menghapus SELURUH akun tamu yang terdaftar? Semua riwayat pemesanan terkait juga akan terhapus.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-2xl shadow-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Bersihkan Semua Tamu</span>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Guests Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-150 text-slate-500 font-bold text-xs uppercase tracking-wider">
                        <th class="p-6 w-16 text-center">No</th>
                        <th class="p-6">Nama Lengkap</th>
                        <th class="p-6">No. KTP / NIK</th>
                        <th class="p-6">Email</th>
                        <th class="p-6">Nomor Telepon / WA</th>
                        <th class="p-6">Tanggal Bergabung</th>
                        <th class="p-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                    @forelse($guests as $index => $guest)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-6 text-center text-slate-400 font-bold">{{ $index + 1 }}</td>
                            <td class="p-6 text-slate-950 font-bold">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($guest->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div>{{ $guest->nama }}</div>
                                        @if(($guest->kewarganegaraan ?? 'WNI') === 'WNA')
                                            <span class="inline-block mt-0.5 px-2 py-0.5 bg-purple-50 text-purple-700 text-[10px] font-extrabold rounded-full border border-purple-200">🌐 WNA (Luar Negeri)</span>
                                        @else
                                            <span class="inline-block mt-0.5 px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-full border border-emerald-200">🇮🇩 WNI</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-6 text-xs text-slate-600">
                                @if(($guest->kewarganegaraan ?? 'WNI') === 'WNA')
                                    <div class="font-mono font-bold text-slate-800">Paspor: {{ $guest->no_paspor ?? '-' }}</div>
                                    <div class="text-[11px] font-medium text-purple-600">Negara: {{ $guest->negara ?? 'Luar Negeri' }}</div>
                                @else
                                    <div class="font-mono font-bold text-slate-800">NIK: {{ $guest->no_ktp ?? '-' }}</div>
                                @endif
                                <div class="text-[11px] font-normal text-slate-500 mt-0.5">📍 {{ $guest->alamat_lengkap_formatted }}</div>
                            </td>
                            <td class="p-6 font-medium text-slate-600">{{ $guest->email ?? '-' }}</td>

                            <td class="p-6 font-medium text-slate-600">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guest->no_hp) }}" target="_blank" class="hover:text-emerald-650 transition-colors flex items-center gap-1">
                                    {{ $guest->no_hp }}
                                </a>
                            </td>
                            <td class="p-6 text-slate-400 font-medium">
                                @if($guest->created_at)
                                    {{ date('d M Y, H:i', strtotime($guest->created_at)) }} WIB
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-6 text-right">
                                <form action="{{ route('admin.guests.destroy', $guest->id_tamu) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun tamu \'{{ $guest->nama }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Hapus Akun Tamu">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada akun tamu yang terdaftar di sistem.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
