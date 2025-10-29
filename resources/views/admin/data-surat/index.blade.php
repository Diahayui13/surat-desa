@extends('layouts.app')
@section('content')
<div class="card p-4">
  <h4>Data Surat</h4>
  <a href="{{ route('data-surat.create') }}" class="btn btn-primary mb-3">Tambah</a>
  <table class="table">
    <thead><tr><th>Judul</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach($surats as $s)
      <tr>
        <td>{{ $s->judul }}</td>
        <td>{{ $s->status }}</td>
        <td>
          <a href="{{ route('data-surat.edit',$s->id) }}">Edit</a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection
