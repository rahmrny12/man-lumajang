@extends('template_backend.home')
@section('heading', 'Details Guru')
@section('page')
    <li class="breadcrumb-item active">
        <a href="{{ route('guru.index') }}">Guru</a>
    </li>
    <li class="breadcrumb-item active">Details Guru</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('guru.index') }}" class="btn btn-default btn-sm"><i class='nav-icon fas fa-arrow-left'></i>
                    &nbsp;
                    Kembali</a>
            </div>
            <div class="card-body">
                <div class="row no-gutters ml-2 mb-2 mr-2">
                    <div class="col-md-4">
                        <img src="{{ asset($guru->foto) }}" class="card-img img-details" alt="{{ $guru->foto }}">
                    </div>
                    <div class="col-md-1 mb-4"></div>
                    <div class="col-md-7">
                        <h5 class="card-title card-text mb-2">Nama : {{ $guru->nama_guru }}</h5>
                        <h5 class="card-title card-text mb-2">NIPM : {{ $guru->nip }}</h5>
                        <h5 class="card-title card-text mb-2">Guru Mapel : @foreach ($guru->mapel as $mapel)
                                <span class="badge badge-info p-2">{{ $mapel->nama_mapel }}</span>
                            @endforeach
                        </h5>
                        <h5 class="card-title card-text mb-2">Tanggal Mulai Kerja : {{ $guru->tmk }}</h5>
                        @if ($guru->jk == 'L')
                            <h5 class="card-title card-text mb-2">Jenis Kelamin : Laki-laki</h5>
                        @else
                            <h5 class="card-title card-text mb-2">Jenis Kelamin : Perempuan</h5>
                        @endif
                        <h5 class="card-title card-text mb-2">Tempat Lahir : {{ $guru->tmp_lahir }}</h5>
                        <h5 class="card-title card-text mb-2">Tanggal Lahir :
                            {{ date('l, d F Y', strtotime($guru->tgl_lahir)) }}</h5>
                        <h5 class="card-title card-text mb-2">No. Telepon : {{ $guru->no_telp }}</h5>
                        <h5 class="card-title card-text mb-2">RFID : {{ $guru->rfid }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#MasterData").addClass("active");
        $("#liMasterData").addClass("menu-open");
        $("#DataGuru").addClass("active");
    </script>
@endsection
