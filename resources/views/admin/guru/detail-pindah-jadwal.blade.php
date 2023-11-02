@extends('template_backend.home')
@section('heading', 'Pindah Jadwal Guru')
@section('page')
    <li class="breadcrumb-item active">Pindah Jadwal guru</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{-- <a href="{{ route('guru.mapel', Crypt::encrypt($data->mapel_id)) }}" class="btn btn-default btn-sm"><i
                        class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</a> --}}
            </div>
            <div class="card-body">
                <div class="row display-flex justify-content-between no-gutters ml-2 mb-2 mr-2">
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Jadwal Awal</h5>
                                <p class="card-text mb-0">
                                    Nama Guru : {{ $data->guru->nama_guru }}
                                </p>
                                <p class="card-text mb-0">
                                    Mapel : {{ $data->mapel->nama_mapel }}
                                </p>
                                <p class="card-text mb-0">
                                    Kelas : {{ $data->kelas->nama_kelas }}
                                </p>
                                <p class="card-text mb-0">
                                    Hari : {{ $data->hari->nama_hari }}
                                </p>
                                <p class="card-text mb-0">
                                    Jam Mulai : {{ $data->jam_mulai }}
                                </p>
                                <p class="card-text mb-0">
                                    Jam Selesai : {{ $data->jam_selesai }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-exchange-alt" style="font-size: 24px;"></i>
                        <div class="d-block float-right my-3">
                            <a href="{{ route('approve', [
                                'status' => 'tolak',
                                'jadwal_id' => $data->id,
                            ]) }}"
                                class="btn btn-danger mx-1">Tolak</a>
                            <a href="{{ route('approve', [
                                'status' => 'setuju',
                                'jadwal_id' => $data->id,
                            ]) }}"
                                class="btn btn-primary mx-1">Setujui</a>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Jadwal Tukar</h5>
                                <p class="card-text mb-0">
                                    Nama Guru : {{ $data->jadwal->guru->nama_guru }}
                                </p>
                                <p class="card-text mb-0">
                                    Mapel : {{ $data->jadwal->mapel->nama_mapel }}
                                </p>
                                <p class="card-text mb-0">
                                    Kelas : {{ $data->jadwal->kelas->nama_kelas }}
                                </p>
                                <p class="card-text mb-0">
                                    Hari : {{ $data->jadwal->hari->nama_hari }}
                                </p>
                                <p class="card-text mb-0">
                                    Jam Mulai : {{ $data->jadwal->jam_mulai }}
                                </p>
                                <p class="card-text mb-0">
                                    Jam Selesai : {{ $data->jadwal->jam_selesai }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#pindahJadwal").addClass("active");
    </script>
@endsection
