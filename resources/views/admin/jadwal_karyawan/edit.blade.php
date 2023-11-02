@extends('template_backend.home')
@section('heading', 'Edit Jadwal')
@section('page')
    <li class="breadcrumb-item active"><a href="{{ route('jadwal.karyawan.index', strtolower($karyawan->role)) }}">Jadwal</a>
    </li>
    <li class="breadcrumb-item active">Edit Jadwal</li>
@endsection
@section('content')
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Data Jadwal</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('jadwalkaryawan.update', $jadwal->id) }}" method="post">
                @csrf
                @method('put')
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hari_id">Hari</label>
                                <select id="hari_id" name="hari_id"
                                    class="form-control @error('hari_id') is-invalid @enderror select2bs4">
                                    <option value="">-- Pilih Hari --</option>
                                    @foreach ($hari as $data)
                                        <option value="{{ $data->id }}"
                                            @if ($jadwal->hari_id == $data->id) selected @endif>{{ $data->nama_hari }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_mulai">Jam Mulai</label>
                                <input type='time' value="{{ $jadwal->jam_mulai }}" id="jam_mulai" name='jam_mulai'
                                    class="form-control @error('jam_mulai') is-invalid @enderror" placeholder='JJ:mm:dd'>
                            </div>
                            <div class="form-group">
                                <label for="jam_selesai">Jam Selesai</label>
                                <input type='time' value="{{ $jadwal->jam_selesai }}" name='jam_selesai'
                                    class="form-control @error('jam_selesai') is-invalid @enderror" placeholder='JJ:mm:dd'>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="#" name="kembali" class="btn btn-default" id="back"><i
                            class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</a> &nbsp;
                    <button name="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp;
                        Update</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var role = "{{ $karyawan->role }}"

        $(document).ready(function() {
            $('#back').click(function() {
                window.location =
                    "{{ route('jadwalkaryawan.show', ['id' => Crypt::encrypt($jadwal->hari_id), 'user_id' => Crypt::encrypt($jadwal->user_id)]) }}";
            });
        });
        $("#MasterData").addClass("active");
        $("#liMasterData").addClass("menu-open");
        $("#DataJadwal" + role.toLowerCase()).addClass("active");
    </script>
@endsection
