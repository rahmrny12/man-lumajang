@extends('layouts.absensi-app')
@section('page', 'MI JAMBEARUM')
@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center">
        <div class="col-md-4">
            <!-- <input type="text" class="form-control" placeholder="Masukkan ID Card" id="input-search-siswa" name="keyword"
                onkeyup="submitAbsen(event, this)" autofocus> -->
            <button type="button" onclick="submitAbsen()" class="btn btn-success w-100">Mulai Absen</button>
        </div>
    </div>

@section('script')
    <script>
        async function submitAbsen() {
            const isSiswaCardShowing = !$("#siswa_card").hasClass('d-none');
            const isGuruCardShowing = !$("#guru_card").hasClass('d-none');
            if (!isSiswaCardShowing && !isGuruCardShowing) {
                try {
                    const loginResult = await loginFinger();
                    const user_id = loginResult.data.user_id;

                    const searchResult = await searchByID(user_id);
                    const type = searchResult.hasOwnProperty('nama_guru') ? 'guru' : 'siswa';
                    if (type === 'siswa') {
                        $('#nama_siswa').html(searchResult.nama_siswa);
                        $('#kelas').html(searchResult.kelas.nama_kelas);
                        $('.jenis_kelamin').html(searchResult.jk === "L" ? "Laki-laki" : "Perempuan");
                        $('#nisn').html(searchResult.nis);
                        $('#foto').attr('src', `{{ asset('') }}` + searchResult.foto);
                    } else {
                        $('#nama_guru').html(searchResult.nama_guru);
                        $('#nip').html(searchResult.nip);
                        $('.jenis_kelamin').html(searchResult.jk === "L" ? "Laki-laki" : "Perempuan");
                        $('#tmp_lahir').html(searchResult.tmp_lahir);
                        $('#tgl_lahir').html(searchResult.tgl_lahir);
                        $('#foto_guru').attr('src', `{{ asset('') }}` + searchResult.foto);
                    }

                    const absensiResult = await sendAbsensi(user_id);
                    showMessageAlert(absensiResult);

                } catch (error) {
                    handleAjaxError(error, "Terjadi kesalahan.");
                } finally {
                    $('#input-search-siswa').val('');
                    removeCard();
                    // setTimeout(submitAbsen, 5000);
                }
            }
        }

        function removeCard() {
            setTimeout(() => {
                $("#siswa_card").addClass('d-none');
                $("#guru_card").addClass('d-none');
            }, 5000);
        }

        function showMessageAlert(response) {
            const type = response.type;
            const message = response.message;

            toastr.options.positionClass = "toast-top-center";
            if (type === 'success') {
                toastr.success(message);
            } else if (type === 'warning') {
                toastr.warning(message);
            } else if (type === 'error') {
                toastr.error(message);
            }
            toastr.options.positionClass = "toast-top-right";
        }

        function loginFinger() {
            const linkFinger = `Fingerryo:absen;http://localhost:8008/get_siswa.php;http://localhost:8008/process_absen.php;R920J08467;G566E169E6BE96B954092DVP;0C563C7698A50A7;5;Absensi Siswa;04-06-2024`;
            window.location.href = linkFinger;

            return new Promise((resolve, reject) => {
                let attempts = 0;
                const maxAttempts = 5;
                const intervalTime = 5000;
                const intervalId = setInterval(() => {
                    $.ajax({
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "type": 'siswa',
                        },
                        url: "{{ url('/absensi-kehadiran/check-finger') }}",
                        success: (result) => {
                            clearInterval(intervalId);
                            resolve(result);
                        },
                        error: (error) => {
                            attempts++;
                            if (attempts >= maxAttempts) {
                                clearInterval(intervalId);
                                reject(error);
                            }
                        },
                    });
                }, intervalTime);
            });
        }

        function searchByID(keyword) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "keyword": keyword,
                        "type": 'siswa',
                    },
                    url: "{{ url('/absensi/search') }}",
                    success: (result) => {
                        if (Object.keys(result).length !== 0) {
                            const type = result.hasOwnProperty('nama_guru') ? 'guru' : 'siswa';
                            if (type === 'siswa') {
                                $("#siswa_card").removeClass('d-none');
                            } else {
                                $("#guru_card").removeClass('d-none');
                            }

                            const today = new Date();
                            const formattedDate = `${today.getDate()}-${today.getMonth() + 1}-${today.getFullYear()}`;
                            $('.tanggal_absen').html("Tanggal : " + formattedDate);

                            const formattedTime = today.toLocaleTimeString('en-US', { hour12: false });
                            $('.waktu_absen').html(formattedTime);

                            resolve(result);
                        }
                    },
                    error: (error) => {
                        reject(error);
                    },
                });
            });
        }

        function sendAbsensi(keyword) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "keyword": keyword,
                        "type": 'siswa',
                    },
                    url: "{{ url('/absensi-kehadiran') }}",
                    success: (result) => {
                        resolve(result);
                    },
                    error: (error) => {
                        reject(error);
                    },
                });
            });
        }

        function handleAjaxError(error, defaultMessage) {
            if (error.status === 404) {
                toastr.error("Data tidak ditemukan.");
            } else {
                toastr.error(defaultMessage + " " + error.status + " " + error.statusText);
            }
        }

        function handleLoginError(error) {
            const errorJson = error.responseJSON;
            if (errorJson && errorJson.type === 'error' && errorJson.message) {
                toastr.error(errorJson.message);
            } else {
                toastr.error("Terjadi kesalahan saat memverifikasi sidik jari. " + error.status + " " + error.statusText);
            }
        }
    </script>
@endsection
@endsection
