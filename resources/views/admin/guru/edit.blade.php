@extends('template_backend.home')
@section('heading', 'Edit Guru')
@section('page')
    <li class="breadcrumb-item active"><a href="{{ route('guru.index') }}">Guru</a></li>
    <li class="breadcrumb-item active">Edit Guru</li>
@endsection
@section('content')
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Data Guru</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('guru.update', $guru->id) }}" method="post">
                @csrf
                @method('patch')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_guru">Nama Guru</label>
                                <input type="text" id="nama_guru" name="nama_guru" value="{{ $guru->nama_guru }}"
                                    class="form-control @error('nama_guru') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="mapel_id">Mapel</label>
                                <select id="mapel_id" name="mapel_id[]"
                                    class="select2bs4 form-control @error('mapel_id') is-invalid @enderror" multiple>
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach ($mapel as $data)
                                        <option value="{{ $data->id }}"
                                            @if (in_array($data->id, $guru->mapel->pluck('id')->toArray())) selected @endif>{{ $data->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tmp_lahir">Tempat Lahir</label>
                                <input type="text" id="tmp_lahir" name="tmp_lahir" value="{{ $guru->tmp_lahir }}"
                                    class="form-control @error('tmp_lahir') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="no_telp">Nomor Telpon/HP</label>
                                <input type="text" id="no_telp" name="no_telp" onkeypress="return inputAngka(event)"
                                    value="{{ $guru->no_telp }}" class="form-control @error('no_telp') is-invalid @enderror">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nip">NIPM</label>
                                <input type="text" id="nip" name="nip" onkeypress="return inputAngka(event)"
                                    value="{{ $guru->nip }}" class="form-control @error('nip') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="jk">Jenis Kelamin</label>
                                <select id="jk" name="jk"
                                    class="select2bs4 form-control @error('jk') is-invalid @enderror">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L" @if ($guru->jk == 'L') selected @endif>Laki-Laki
                                    </option>
                                    <option value="P" @if ($guru->jk == 'P') selected @endif>Perempuan
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tgl_lahir">Tanggal Lahir</label>
                                <input type="date" id="tgl_lahir" name="tgl_lahir" value="{{ $guru->tgl_lahir }}"
                                    class="form-control @error('tgl_lahir') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="tmk">Tanggal Mulai Kerja</label>
                                <input type="date" id="tmk" name="tmk" value="{{ $guru->tmk }}"
                                    class="form-control @error('tmk') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="rfid">RFID</label>
                                <input type="text" id="rfid" name="rfid" value="{{ $guru->rfid }}"
                                    class="form-control @error('rfid') is-invalid @enderror">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('guru.index') }}" name="kembali" class="btn btn-default" id="back"><i
                            class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</a> &nbsp;
                    <button name="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp;
                        Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#back').click(function() {
                window.location = "{{ route('guru.mapel', Crypt::encrypt($guru->mapel_id)) }}";
            });
        });
        $("#MasterData").addClass("active");
        $("#liMasterData").addClass("menu-open");
        $("#DataGuru").addClass("active");
    </script>
@endsection
