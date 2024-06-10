@extends('layouts.absensi-app')
@section('page', 'MI JAMBEARUM')
@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Masukkan ID Card" id="input-search-siswa" name="keyword"
                onkeyup="submitAbsen(event, this)" autofocus>
        </div>
    </div>

@section('script')
    <script>
        function submitAbsen(e, keyword) {
            const isSiswaCardShowing = !$("#siswa_card").hasClass('d-none');
            const isGuruCardShowing = !$("#guru_card").hasClass('d-none');
            if (!isSiswaCardShowing && !isGuruCardShowing) {
                if (e.key === 'Enter') {
                    searchByRFID(keyword.value)
                        .then(async (result) => {
                            const type = result.hasOwnProperty('nama_guru') ? 'guru' : 'siswa';
                            if (type == 'siswa') {
                                $('#nama_siswa').html(result.nama_siswa);
                                $('#kelas').html(result.kelas.nama_kelas);
                                $('.jenis_kelamin').html(result.jk == "L" ? "Laki-laki" : "Perempuan");
                                $('#nisn').html(result.nis);
                                $('#foto').attr('src', `{{ asset('') }}` + result.foto);

                                await loginFinger(result.id)
                                    .then(showMessageAlert)
                                    .then(async (_) => {
                                        await sendAbsensi(keyword)
                                            .then(showMessageAlert)
                                            .catch(error => {
                                                toastr.error(
                                                    "Terjadi kesalahan saat mengirim absensi. " +
                                                    error.status ==
                                                    undefined ? '' : error.status + " " + error
                                                    .statusText ==
                                                    undefined ? '' : error.statusText)
                                            })
                                            .finally(removeCard)
                                    })
                                    .catch(error => {
                                        removeCard()
                                        const errorJson = error.responseJSON;
                                        if (errorJson && errorJson.hasOwnProperty('type') && errorJson
                                            .hasOwnProperty('message') && errorJson.type == 'error') {
                                            toastr.error(errorJson.message)
                                        } else {
                                            toastr.error("Terjadi kesalahan saat memverifikasi sidik jari. " +
                                                error
                                                .status ==
                                                undefined ? '' : error.status + " " + error.statusText ==
                                                undefined ? '' : error.statusText)
                                        }
                                    })
                            } else {
                                $('#nama_guru').html(result.nama_guru);
                                $('#nip').html(result.nip);
                                $('.jenis_kelamin').html(result.jk == "L" ? "Laki-laki" : "Perempuan");
                                $('#tmp_lahir').html(result.tmp_lahir);
                                $('#tgl_lahir').html(result.tgl_lahir);
                                $('#foto_guru').attr('src', `{{ asset('') }}` + result.foto);
                            }

                            function removeCard() {
                                const removeCard = setTimeout(() => {
                                    $("#siswa_card").addClass('d-none');
                                    $("#guru_card").addClass('d-none');
                                }, 2000);
                            }

                            function showMessageAlert(response) {
                                const type = response.type;
                                const message = response.message;

                                toastr.options.positionClass = "toast-top-center";
                                if (type == 'success') {
                                    toastr.success(message)
                                } else if (type == 'warning') {
                                    toastr.warning(message)
                                } else if (type == 'error') {
                                    toastr.error(message)
                                }
                                toastr.options.positionClass = "toast-top-right";
                            }
                        })
                        .catch(error => {
                            if (error.status == 404) {
                                toastr.error("Data tidak ditemukan.")
                            } else {
                                console.log(error)
                                toastr.error("Terjadi kesalahan saat mengambil data. " + error.status + " " + error
                                    .statusText)
                            }
                        })
                        .finally(() => {
                            $('#input-search-siswa').val('')
                        })
                }
            } else {
                $('#input-search-siswa').val('')
            }
        }

        function loginFinger(userId) {
            let urlVerification = btoa(`{{ url('verification.php?user_id=${userId}') }}`)
            let linkFinger = `Fingerryo:absen;http://localhost:8008/get_siswa.php;http://localhost:8008/process_absen.php;R920J08467;G566E169E6BE96B954092DVP;0C563C7698A50A7;5;Absensi Siswa;04-06-2024`

            window.location.href = linkFinger;

            return new Promise((resolve, reject) => {
                var limit = 5;
                var ct = 0;
                var timeout = 3000;

                const intervalId = setInterval(() => {
                    $.ajax({
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "type": 'siswa',
                            "id": userId
                        },
                        url: "{{ url('/absensi-kehadiran/check-finger') }}",
                        success: function(result) {
                            clearInterval(intervalId);
                            resolve(result);
                        },
                        error: function(error) {
                            ct++;
                            if (ct >= limit) {
                                clearInterval(intervalId);
                                reject(error);
                            }
                        },
                    });
                }, timeout);
            });
        }

        function searchByRFID(keyword) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "keyword": keyword,
                    },
                    url: "{{ url('/absensi/search') }}",
                    success: function(result) {
                        if (Object.keys(result).length != 0) {
                            const type = result.hasOwnProperty('nama_guru') ? 'guru' : 'siswa';

                            if (type == 'siswa') {
                                $("#siswa_card").removeClass('d-none');
                            } else {
                                $("#guru_card").removeClass('d-none');
                            }

                            let today = new Date();
                            let formattedDate = today.getDate() + '-' + (today.getMonth() + 1) + '-' +
                                today.getFullYear();
                            $('.tanggal_absen').html("Tanggal : " + formattedDate);

                            let formattedTime = today.toLocaleTimeString('en-US', {
                                hour12: false
                            });
                            $('.waktu_absen').html(formattedTime);

                            resolve(result);
                        }
                    },
                    error: function(error) {
                        reject(error);
                    },
                });
            });
        }

        function sendAbsensi(e) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "keyword": e.value,
                    },
                    url: "{{ url('/absensi-kehadiran') }}",
                    success: function(result) {
                        resolve(result);
                    },
                    error: function(error) {
                        reject(error);
                    },
                    complete: function() {}
                });
            });
        }
    </script>
@endsection
@endsection
