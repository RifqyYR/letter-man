<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<!-- Bootstrap core JavaScript-->
<script src="<?php echo e(url('backend/vendor/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(url('backend/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo e(url('backend/vendor/jquery-easing/jquery.easing.min.js')); ?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo e(url('backend/js/sb-admin-2.js')); ?>"></script>


<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js"></script>


<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>


<script src="//pagination.js.org/dist/2.6.0/pagination.js"></script>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    <?php if(session()->has('success')): ?>
        toastr.success('<?php echo e(session('success')); ?>', 'BERHASIL!');
    <?php elseif(session()->has('error')): ?>
        toastr.error('<?php echo e(session('error')); ?>', 'GAGAL!');
    <?php endif; ?>

    FilePond.registerPlugin(FilePondPluginFileValidateType);
    FilePond.create(document.querySelector('input[name="fileLampiran[]"]'), {
        chunkUploads: true
    });

    FilePond.setOptions({
        acceptedFileTypes: ['application/pdf'],
        server: {
            url: "/upload-kd",
            headers: {
                'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
            },
            revert: {
                url: '/kebenaran-dokuman/delete-tmp',
                headers: {
                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                }
            }
        },
        acceptedFileTypes: ["application/pdf"],
        fileValidateTypeLabelExpectedTypesMap: {
            'application/pdf': '.pdf',
        },
        maxFiles: 10,
        labelIdle: `Seret file ke sini atau <span class="filepond--label-action"> Pilih file </span><br>Maksimal 4 file`,
        allowMultiple: true,
    });

    function hapusUser(id) {
        const link = document.getElementById('deleteUserLink');
        link.href = "/delete-user/" + id;
    }

    function deleteOfficialMemo(id) {
        const link = document.getElementById('deleteOfficialMemoLink');
        link.href = "/nota-dinas/hapus/" + id;
    }

    function deleteNews(id) {
        const link = document.getElementById('deleteNewsLink');
        link.href = "/berita-acara/hapus/" + id;
    }

    function deleteOutgoingMail(id) {
        const link = document.getElementById('deleteOutgoingMailLink');
        link.href = "/surat-keluar/hapus/" + id;
    }

    function deleteDocumentAuthorizationLetter(id) {
        const link = document.getElementById('deleteDocumentAuthorizationLetterLink');
        link.href = "/kebenaran-dokumen/hapus/" + id;
    }

    function moneyFormat(input) {
        let value = input.value.replace(/[^0-9.]/g, '');

        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        input.value = value;
    }

    function numberToRomanRepresentation(number) {
        const map = {
            'M': 1000,
            'CM': 900,
            'D': 500,
            'CD': 400,
            'C': 100,
            'XC': 90,
            'L': 50,
            'XL': 40,
            'X': 10,
            'IX': 9,
            'V': 5,
            'IV': 4,
            'I': 1
        };

        let returnValue = '';

        while (number > 0) {
            for (const roman in map) {
                if (number >= map[roman]) {
                    number -= map[roman];
                    returnValue += roman;
                    break;
                }
            }
        }

        return returnValue;
    }

    $('#btnSetReadonly').click(function() {
        var isReadonly = $('#nomorSurat').prop('readonly');
        $('#nomorSurat').prop('readonly', !isReadonly)
    });

    $('#editable-select').editableSelect().on('select.editable-select', function(elem, li, e) {
        id = li.context.attributes[0].value;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/tambah-kebenaran-dokumen/vendor',
            method: 'POST',
            data: {
                vendorId: id
            },
            success: function(data) {
                $('#bankPenerima').val(data.vendor.bank_name);
                $('#nomorRekening').val(data.vendor.account_number);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });

    $('#tanggalPembuatan, #unitKerja').on('change', function() {
        var createdDateVal = $('#tanggalPembuatan').val() == '' ? new Date().toJSON().slice(0, 10) : $(
            '#tanggalPembuatan').val();
        var unitKerja = $('#unitKerja').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (window.location.href.indexOf("nota-dinas") > -1) {
            $.ajax({
                url: '/nota-dinas/penomoran',
                method: 'POST',
                data: {
                    dateData: createdDateVal,
                    unitKerjaData: unitKerja,
                },
                success: function(data) {
                    $('#nomorSurat').val(data.officialMemoNumber);
                },
                error: function(xhr, status, error) {
                    // Handle errors if the request fails
                    console.log(error);
                }
            });
        } else if (window.location.href.indexOf("berita-acara") > -1) {
            $.ajax({
                url: '/berita-acara/penomoran',
                method: 'POST',
                data: {
                    dateData: createdDateVal,
                    unitKerjaData: unitKerja,
                },
                success: function(data) {
                    $('#nomorSurat').val(data.newsNumber);
                },
                error: function(xhr, status, error) {
                    // Handle errors if the request fails
                    console.log(error);
                }
            });
        } else if (window.location.href.indexOf("kebenaran-dokumen") > -1) {
            $.ajax({
                url: '/kebenaran-dokumen/penomoran',
                method: 'POST',
                data: {
                    dateData: createdDateVal,
                    unitKerjaData: unitKerja,
                },
                success: function(data) {
                    $('#nomorSurat').val(data.documentAuthorizationLetterNumber);
                },
                error: function(xhr, status, error) {
                    // Handle errors if the request fails
                    console.log(error);
                }
            });
        } else if (window.location.href.indexOf("surat-keluar") > -1) {
            $.ajax({
                url: '/surat-keluar/penomoran',
                method: 'POST',
                data: {
                    dateData: createdDateVal,
                    unitKerjaData: unitKerja,
                },
                success: function(data) {
                    $('#nomorSurat').val(data.outgoingMailNumber);
                },
                error: function(xhr, status, error) {
                    // Handle errors if the request fails
                    console.log(error);
                }
            });
        }
    });
</script>

<script>
    $('#search,#searchD').on('keyup', function() {
        search();
    });
    search();

    function search() {
        var keyword = $('#search,#searchD').val();
        if (window.location.pathname == "/nota-dinas") {
            $.post('<?php echo e(route('officialmemo.search')); ?>', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    keyword: keyword
                },
                function(data) {
                    console.log(data);
                    table_post_row_official_memo(data);
                });
        } else if (window.location.pathname == "/berita-acara") {
            $.post('<?php echo e(route('news.search')); ?>', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    keyword: keyword
                },
                function(data) {
                    table_post_row_news(data);
                });
        } else if (window.location.pathname == "/surat-keluar") {
            $.post('<?php echo e(route('outgoingmail.search')); ?>', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    keyword: keyword
                },
                function(data) {
                    table_post_row_outgoing_mails(data);
                });
        } else if (window.location.pathname == "/kebenaran-dokumen") {
            $.post('<?php echo e(route('documentauthorizationletter.search')); ?>', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    keyword: keyword
                },
                function(data) {
                    table_post_row_document_authorization_letters(data);
                });
        }

        $(document).keypress(
            function(event) {
                if (event.which == '13') {
                    event.preventDefault();
                }
            });
    }

    function table_post_row_official_memo(res) {
        var options = {
            dataSource: res.officialMemos,
            pageSize: 10,
            showSizeChanger: true,
            callback: function(data, pagination) {
                var htmlView = `<tr>`;

                if (data.length == 0) {
                    htmlView += `
                        <tr>
                            <td colspan="7">Tidak ada data.</td>
                        </tr>`;
                }
                $.each(data, function(index, item) {
                    htmlView += `
                        <td>${index + 1}</td>
                        <td>${item.title}</td>
                        <td>${item.number}</td>
                        <td>${item.created_by}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <a href="<?php echo e(url('/nota-dinas/` + item.id +`')); ?>"><button type="button"
                                        class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                            viewBox="0 0 576 512">
                                            <style>
                                                svg {
                                                    fill: #ffffff
                                                }
                                            </style>
                                            <path
                                                d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                                        </svg>
                                    </button></a>
                                    <a href="<?php echo e(url('/nota-dinas/ubah/` + item.id +`')); ?>" class="mx-1"><button
                                            type="button" class="btn btn-warning btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                viewBox="0 0 512 512">
                                                <style>
                                                    svg {
                                                        fill: #ffffff
                                                    }
                                                </style>
                                                <path
                                                    d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                                            </svg>
                                        </button></a>
                                <?php if(Auth::user()->role != 0): ?>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#deleteOfficialMemoModal"
                                        onclick="deleteOfficialMemo('` + item.id + `')">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                            viewBox="0 0 448 512">
                                            <style>
                                                svg {
                                                    fill: #ffffff
                                                }
                                            </style>
                                            <path
                                                d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    `;
                    htmlView += `</tr>`;
                });
                $('tbody').html(htmlView);
            }
        }
        $('#demo').pagination(options);
    }

    function table_post_row_news(res) {
        var options = {
            dataSource: res.news,
            pageSize: 10,
            showSizeChanger: true,
            callback: function(data, pagination) {
                var htmlView = `<tr>`;

                if (data.length == 0) {
                    htmlView += `
                        <tr>
                            <td colspan="7">Tidak ada data.</td>
                        </tr>`;
                }
                $.each(data, function(index, item) {
                    htmlView += `
                        <td>${index + 1}</td>
                        <td>${item.title}</td>
                        <td>${item.number}</td>
                        <td>${item.created_by}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <a href="<?php echo e(url('/berita-acara/` + item.id +`')); ?>"><button type="button"
                                        class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                            viewBox="0 0 576 512">
                                            <style>
                                                svg {
                                                    fill: #ffffff
                                                }
                                            </style>
                                            <path
                                                d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                                        </svg>
                                    </button></a>
                                    <a href="<?php echo e(url('/berita-acara/ubah/` + item.id +`')); ?>" class="mx-1"><button
                                            type="button" class="btn btn-warning btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                viewBox="0 0 512 512">
                                                <style>
                                                    svg {
                                                        fill: #ffffff
                                                    }
                                                </style>
                                                <path
                                                    d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                                            </svg>
                                        </button></a>
                                <?php if(Auth::user()->role != 0): ?>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#deleteNewsModal"
                                        onclick="deleteNews('` + item.id + `')">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                            viewBox="0 0 448 512">
                                            <style>
                                                svg {
                                                    fill: #ffffff
                                                }
                                            </style>
                                            <path
                                                d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    `;
                    htmlView += `</tr>`;
                });
                $('tbody').html(htmlView);
            }
        }
        $('#demo').pagination(options);
    }

    function table_post_row_outgoing_mails(res) {
        var options = {
            dataSource: res.outgoingmails,
            pageSize: 10,
            showSizeChanger: true,
            callback: function(data, pagination) {
                var htmlView = `<tr>`;

                if (data.length == 0) {
                    htmlView += `
                        <tr>
                            <td colspan="7">Tidak ada data.</td>
                        </tr>`;
                }
                $.each(data, function(index, item) {
                    htmlView += `
                        <td>${index + 1}</td>
                        <td>${item.title}</td>
                        <td>${item.number}</td>
                        <td>${item.created_by}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <a href="<?php echo e(url('/surat-keluar/` + item.id +`')); ?>"><button type="button"
                                        class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                            viewBox="0 0 576 512">
                                            <style>
                                                svg {
                                                    fill: #ffffff
                                                }
                                            </style>
                                            <path
                                                d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                                        </svg>
                                    </button></a>
                                    <a href="<?php echo e(url('/surat-keluar/ubah/` + item.id +`')); ?>" class="mx-1"><button
                                            type="button" class="btn btn-warning btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                viewBox="0 0 512 512">
                                                <style>
                                                    svg {
                                                        fill: #ffffff
                                                    }
                                                </style>
                                                <path
                                                    d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                                            </svg>
                                        </button></a>
                                <?php if(Auth::user()->role != 0): ?>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#deleteOutgoingMailModal"
                                        onclick="deleteOutgoingMail('` + item.id + `')">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                            viewBox="0 0 448 512">
                                            <style>
                                                svg {
                                                    fill: #ffffff
                                                }
                                            </style>
                                            <path
                                                d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    `;
                    htmlView += `</tr>`;
                });
                $('tbody').html(htmlView);
            }
        }
        $('#demo').pagination(options);
    }

    function table_post_row_document_authorization_letters(res) {
        var options = {
            dataSource: res.documentAuthorizationLetters,
            pageSize: 10,
            showSizeChanger: true,
            callback: function(data, pagination) {
                var htmlView = `<tr>`;

                if (data.length == 0) {
                    htmlView += `
                        <tr>
                            <td colspan="7">Tidak ada data.</td>
                        </tr>`;
                }
                $.each(data, function(index, item) {
                    htmlView += `
                        <td>${index + 1}</td>
                        <td>${item.title}</td>
                        <td>${item.number}</td>
                        <td>${item.created_by}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <a href="<?php echo e(url('/kebenaran-dokumen/` + item.id +`')); ?>"><button type="button"
                                        class="btn btn-primary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                            viewBox="0 0 576 512">
                                            <style>
                                                svg {
                                                    fill: #ffffff
                                                }
                                            </style>
                                            <path
                                                d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                                        </svg>
                                    </button></a>
                                    <a href="<?php echo e(url('/kebenaran-dokumen/ubah/` + item.id +`')); ?>" class="mx-1"><button
                                            type="button" class="btn btn-warning btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                viewBox="0 0 512 512">
                                                <style>
                                                    svg {
                                                        fill: #ffffff
                                                    }
                                                </style>
                                                <path
                                                    d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                                            </svg>
                                        </button></a>
                                <?php if(Auth::user()->role != 0): ?>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#deleteDocumentAuthorizationLetterModal"
                                        onclick="deleteDocumentAuthorizationLetter('` + item.id + `')">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                            viewBox="0 0 448 512">
                                            <style>
                                                svg {
                                                    fill: #ffffff
                                                }
                                            </style>
                                            <path
                                                d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    `;
                    htmlView += `</tr>`;
                });
                $('tbody').html(htmlView);
            }
        }
        $('#demo').pagination(options);
    }
</script>
<?php /**PATH D:\APLIKASI\www\siap\resources\views/includes/script.blade.php ENDPATH**/ ?>