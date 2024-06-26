@extends('template_backend.home')
@section('heading', 'Data Guru')
@section('page')
    <li class="breadcrumb-item active">Data Guru</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                        data-target=".bd-example-modal-lg">
                        <i class="nav-icon fas fa-folder-plus"></i> &nbsp; Tambah Data Guru
                    </button>
                    <a href="{{ route('guru.export_excel') }}" class="btn btn-success btn-sm my-3" target="_blank"><i
                            class="nav-icon fas fa-file-export"></i> &nbsp; EXPORT EXCEL</a>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importExcel">
                        <i class="nav-icon fas fa-file-import"></i> &nbsp; IMPORT EXCEL
                    </button>
                    {{-- <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#dropTable">
                        <i class="nav-icon fas fa-minus-circle"></i> &nbsp; Drop
                    </button> --}}
                </h3>
            </div>
            <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{ route('guru.import_excel') }}" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                            </div>
                            <div class="modal-body">
                                @csrf
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h5 class="modal-title">Petunjuk :</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul>
                                            <li>rows 1 = nama guru</li>
                                            <li>rows 2 = nipm guru</li>
                                            <li>rows 3 = jenis kelamin</li>
                                            <li>rows 4 = mata pelajaran</li>
                                        </ul>
                                    </div>
                                </div>
                                <label>Pilih file excel</label>
                                <div class="form-group">
                                    <input type="file" name="file" required="required">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Import</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="dropTable" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{ route('guru.deleteAll') }}">
                        @csrf
                        @method('delete')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Sure you drop all data?</h5>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cencel</button>
                                <button type="submit" class="btn btn-danger">Drop</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Id Card</th>
                            <th>NIPM</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($guru as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->nama_guru }}</td>
                                <td>{{ $data->id_card }}</td>
                                <td>{{ $data->nip }}</td>
                                <td>
                                    <a href="{{ asset($data->foto) }}" data-toggle="lightbox"
                                        data-title="Foto {{ $data->nama_guru }}" data-gallery="gallery"
                                        data-footer='<a href="{{ route('guru.ubah-foto', Crypt::encrypt($data->id)) }}" id="linkFotoGuru" class="btn btn-link btn-block btn-light"><i class="nav-icon fas fa-file-upload"></i> &nbsp; Ubah Foto</a>'>
                                        <img src="{{ asset($data->foto) }}" width="130px" class="img-fluid mb-2">
                                    </a>
                                    {{-- https://siakad.didev.id/guru/ubah-foto/{{$data->id}} --}}
                                </td>
                                <td>
                                    <form action="{{ route('guru.destroy', $data->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <a href="{{ route('guru.show', Crypt::encrypt($data->id)) }}"
                                            class="btn btn-info btn-sm mt-2"><i class="nav-icon fas fa-id-card"></i> &nbsp;
                                            Detail</a>
                                        <a href="{{ route('guru.edit', Crypt::encrypt($data->id)) }}"
                                            class="btn btn-success btn-sm mt-2"><i class="nav-icon fas fa-edit"></i> &nbsp;
                                            Edit</a>
                                        <button class="btn btn-danger btn-sm mt-2"><i class="nav-icon fas fa-trash-alt"></i>
                                            &nbsp; Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Guru</th>
                            <th>Lihat Mapel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mapel as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->nama_mapel }}</td>
                                <td>
                                    <a href="{{ route('guru.mapel', Crypt::encrypt($data->id)) }}"
                                        class="btn btn-info btn-sm"><i class="nav-icon fas fa-search-plus"></i> &nbsp;
                                        Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> --}}
        </div>
    </div>

    <!-- Extra large modal -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Guru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('guru.store') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_guru">Nama Guru</label>
                                    <input type="text" id="nama_guru" name="nama_guru"
                                        class="form-control @error('nama_guru') is-invalid @enderror"
                                        value="{{ old('nama_guru') }}">
                                </div>
                                <div class="form-group">
                                    <label for="tempat_lahir">Tempat Lahir</label>
                                    <input type="text" id="tempat_lahir" name="tempat_lahir"
                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        value="{{ old('tempat_lahir') }}">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                        class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                        value="{{ old('tanggal_lahir', date('Y-m-d')) }}">
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin"
                                        class="form-control @error('jenis_kelamin') is-invalid @enderror">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="L" @if (old('jenis_kelamin') == 'L') selected @endif>Laki-Laki
                                        </option>
                                        <option value="P" @if (old('jenis_kelamin') == 'P') selected @endif>Perempuan
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="no_telp">Nomor Telpon/HP</label>
                                    <input type="text" id="no_telp" name="no_telp"
                                        onkeypress="return inputAngka(event)"
                                        class="form-control @error('no_telp') is-invalid @enderror"
                                        value="{{ old('no_telp') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nipm">NIPM</label>
                                    <input type="text" id="nipm" name="nipm"
                                        onkeypress="return inputAngka(event)"
                                        class="form-control @error('nipm') is-invalid @enderror"
                                        value="{{ old('nipm') }}">
                                </div>
                                <div class="form-group">
                                    <label for="mapel">Mapel</label>
                                    <select id="mapel" name="mapel[]"
                                        class="select2bs4 form-control @error('mapel') is-invalid @enderror"
                                        id="multiple-checkboxes" multiple="multiple">
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach ($mapel as $data)
                                            <option value="{{ $data->id }}"
                                                @if (old('mapel') == $data->id) selected @endif>{{ $data->nama_mapel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_mulai_kerja">Tanggal Mulai Kerja</label>
                                    <input type="date" id="tanggal_mulai_kerja" name="tanggal_mulai_kerja"
                                        class="form-control @error('tanggal_mulai_kerja') is-invalid @enderror"
                                        value="{{ old('tanggal_mulai_kerja', date('Y-m-d')) }}">
                                </div>
                                <div class="form-group">
                                    <label for="foto">File input</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="foto"
                                                class="custom-file-input @error('foto') is-invalid @enderror"
                                                id="foto">
                                            <label class="custom-file-label" for="foto">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rfid">RFID</label>
                                    <input type="text" id="rfid" name="rfid"
                                        onkeypress="return inputAngka(event)"
                                        class="form-control @error('rfid') is-invalid @enderror"
                                        value="{{ old('rfid') }}">
                                </div>
                                @php
                                    $kode = $max + 1;
                                    if (strlen($kode) == 1) {
                                        $id_card = '0000' . $kode;
                                    } elseif (strlen($kode) == 2) {
                                        $id_card = '000' . $kode;
                                    } elseif (strlen($kode) == 3) {
                                        $id_card = '00' . $kode;
                                    } elseif (strlen($kode) == 4) {
                                        $id_card = '0' . $kode;
                                    } else {
                                        $id_card = $kode;
                                    }
                                @endphp
                                <div class="form-group">
                                    <label for="id_card">Nomor ID Card</label>
                                    <input type="text" id="id_card" name="id_card" maxlength="5"
                                        onkeypress="return inputAngka(event)" value="{{ $id_card }}"
                                        class="form-control @error('id_card') is-invalid @enderror" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                class='nav-icon fas fa-arrow-left'></i> &nbsp; Kembali</button>
                        <button type="submit" class="btn btn-primary"><i class="nav-icon fas fa-save"></i> &nbsp;
                            Tambahkan</button>
                    </div>
                </form>
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
