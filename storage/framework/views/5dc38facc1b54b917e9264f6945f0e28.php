
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
</script>
<?php /**PATH C:\Users\muham\Documents\Programming\project\pelindo\e-agreement\resources\views/includes/script.blade.php ENDPATH**/ ?>