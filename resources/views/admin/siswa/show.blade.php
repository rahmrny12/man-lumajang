@extends('template_backend.home')
@section('heading')
    Data Siswa {{ $kelas->nama_kelas }}
@endsection
@section('page')
    <li class="breadcrumb-item active"><a href="{{ route('siswa.index') }}">Siswa</a></li>
    <li class="breadcrumb-item active">{{ $kelas->nama_kelas }}</li>
@endsection
@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('siswa.index') }}" class="btn btn-default btn-sm"><i class="nav-icon fas fa-arrow-left"></i>
                    &nbsp; Kembali</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Siswa</th>
                            <th>No Induk</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswa as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->nama_siswa }}</td>
                                <td>{{ $data->no_induk }}</td>
                                <td class="d-none" id="user_finger_{{ $data->id }}">
                                    {{ $data->fingers == null ? '' : $data->fingers->count() }}</td>
                                <td>
                                    <a href="{{ asset($data->foto) }}" data-toggle="lightbox"
                                        data-title="Foto {{ $data->nama_siswa }}" data-gallery="gallery"
                                        data-footer='<a href="{{ route('siswa.ubah-foto', Crypt::encrypt($data->id)) }}" id="linkFotoGuru" class="btn btn-link btn-block btn-light"><i class="nav-icon fas fa-file-upload"></i> &nbsp; Ubah Foto</a>'>
                                        <img src="{{ asset($data->foto) }}" width="130px" class="img-fluid mb-2">
                                    </a>
                                    {{-- https://siakad.didev.id/siswa/ubah-foto/{{$data->id}} --}}
                                </td>
                                <td>
                                    <form action="{{ route('siswa.destroy', $data->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <div class="row">
                                            @if ($data->fingers->count())
                                                <button type="button"
                                                    class="login_finger ml-2 btn btn-primary btn-sm mt-2"
                                                    data-id="{{ $data->id }}"><i
                                                        class="nav-icon fas fa-fingerprint"></i>
                                                    &nbsp; Login</button>
                                                <button type="button"
                                                    class="register_finger ml-2 btn btn-primary btn-sm mt-2"
                                                    data-id="{{ $data->id }}" data-nama="{{ $data->nama_siswa }}"><i
                                                        class="nav-icon fas fa-fingerprint"></i>
                                                    &nbsp; Registrasi Ulang Finger</button>
                                            @else
                                                <button type="button"
                                                    class="register_finger ml-2 btn btn-warning btn-sm mt-2"
                                                    data-id="{{ $data->id }}" data-nama="{{ $data->nama_siswa }}"><i
                                                        class="nav-icon fas fa-fingerprint"></i>
                                                    &nbsp; Registrasi Finger</button>
                                            @endif
                                            <a href="{{ route('siswa.show', Crypt::encrypt($data->id)) }}"
                                                class="ml-2 btn btn-info btn-sm mt-2"><i
                                                    class="nav-icon fas fa-id-card"></i> &nbsp;
                                                Detail</a>
                                        </div>
                                        <div class="row">
                                            <a href="{{ route('siswa.edit', Crypt::encrypt($data->id)) }}"
                                                class="ml-2 btn btn-success btn-sm mt-2"><i
                                                    class="nav-icon fas fa-edit"></i>
                                                &nbsp; Edit</a>
                                            <button class="ml-2 btn btn-danger btn-sm mt-2"><i
                                                    class="nav-icon fas fa-trash-alt"></i> &nbsp; Hapus</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div id="popupmodal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Pop Up Model</h4>
                </div>
                <div class="modal-body">
                    <p>This is the pop upmodel that'll be added on your webpage.</p>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".login_finger").on("click", loginFinger)

            function loginFinger() {
                let user_id = $(this).data("id");

                let url_verification = btoa(`{{ url('verification.php?user_id=${user_id}') }}`);
                let link_finger = `finspot:FingerspotVer;${url_verification}`

                window.location.href = link_finger;
            }
            
            $(".register_finger").on("click", registerFinger)

            function registerFinger() {
                let user_id = $(this).data("id");
                let nama_siswa = $(this).data("nama");

                let url_register = btoa(`{{ url('register_finger.php?user_id=${user_id}') }}`);
                // let url_register = btoa("<?= url('/finger/register?user_id=1') ?>");
                let link_finger = `finspot:FingerspotReg;${url_register}`

                window.location.href = link_finger;
                user_register(user_id, nama_siswa)
            }
        })

        function user_register(user_id, name) {
            $('.loading-overlay').show();
            let timer_register;

            regStats = 0;
            regCt = -1;
            try {
                clearInterval(timer_register)
            } catch (err) {
                console.log('Registration timer has been init');
            }

            var limit = 4;
            var ct = 1;
            var timeout = 5000;

            timer_register = setInterval(function() {
                console.log("'" + name + "' registration checking...");
                user_checkregister(user_id, $(`#user_finger_${user_id}`).html());
                if (ct >= limit || regStats == 1) {
                    clearInterval(timer_register)
                    console.log("'" + name + "' registration checking end");
                    if (ct >= limit && regStats == 0) {
                        alert("'" + name + "' registrasi gagal");
                        $('.loading-overlay').hide();
                    }
                    if (regStats == 1) {
                        $("#user_finger_" + user_id).html(regCt);
                        alert("registrasi finger print '" + name + "' berhasil!");
                        $('.loading-overlay').hide();
                        location.reload()
                    }
                }
                ct++;
            }, timeout);
        }

        function user_checkregister(user_id, current) {
            $.ajax({
                url: `{{ url('/finger/check-user?user_id=${user_id}&current=${current}') }}`,
                type: "GET",
                success: function(data) {
                    try {
                        if (data.result) {
                            regStats = 1;
                            regCt = data.current;
                        }
                    } catch (err) {
                        alert(err.message);
                    }
                }
            });
        }

        $("#MasterData").addClass("active");
        $("#liMasterData").addClass("menu-open");
        $("#DataSiswa").addClass("active");
    </script>
@endsection
