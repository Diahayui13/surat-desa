@extends('layouts.admin')

@section('content')
<div class="p-6 bg-gradient-to-br from-blue-50 to-blue-50 min-h-screen">
    <h1 class="text-2xl font-bold text-center text-blue-700 mb-6">👥 Data Warga</h1>

    <div class="bg-white p-4 rounded-2xl shadow-md">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-100 text-gray-700">
                    <th class="p-3">No</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3">NIK</th>
                    <th class="p-3">Alamat</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-blue-50">
                    <td class="p-3">1</td>
                    <td class="p-3">Giska Puji</td>
                    <td class="p-3">351xxxxxxxxx</td>
                    <td class="p-3">Desa Digital</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
