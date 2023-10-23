
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<!-- Bootstrap core JavaScript-->
<script src="<?php echo e(url('backend/vendor/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(url('backend/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>


<script src="https://pagination.js.org/dist/2.6.0/pagination.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo e(url('backend/vendor/jquery-easing/jquery.easing.min.js')); ?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo e(url('backend/js/sb-admin-2.js')); ?>"></script>


<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    <?php if(session()->has('success')): ?>
        toastr.success('<?php echo e(session('success')); ?>', 'BERHASIL!');
    <?php elseif(session()->has('error')): ?>
        toastr.error('<?php echo e(session('error')); ?>', 'GAGAL!');
    <?php endif; ?>

    function hapusUser(id) {
        const link = document.getElementById('deleteUserLink');
        link.href = "/delete-user/" + id;
    }

    function deleteOfficialMemo(id) {
        const link = document.getElementById('deleteOfficialMemoLink');
        link.href = "/nota-dinas/hapus/" + id;
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

    $('#tanggalPembuatan').on('change', function() {
        var createdDateVal = $('#tanggalPembuatan').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/nota-dinas/penomoran',
            method: 'POST',
            data: {
                dateData: createdDateVal
            },
            success: function(data) {
                $('#nomorSurat').val(data.officialMemoNumber);
            },
            error: function(xhr, status, error) {
                // Handle errors if the request fails
                console.log(error);
            }
        });

        // $.post('<?php echo e(route('officialmemo.numbering')); ?>', {
        //         _token: $('meta[name="csrf-token"]').attr('content'),
        //         dateData: createdDateVal,
        //     },
        //     function(data) {
        //         $('#nomorSurat').val(data.officialMemoNumber);
        //     });
    });
</script>
<?php /**PATH C:\Users\muham\Documents\Programming\project\pelindo\e-agreement\resources\views/includes/script.blade.php ENDPATH**/ ?>