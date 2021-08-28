document.addEventListener("DOMContentLoaded", function () {
    console.log('DOMContentLoaded');
    $(document).on('click', '.order-info-show', function (e) {
        var $this = $(this);
        Swal.fire({
            html: $this.attr('data-info')
        });
    });

    $(document).on('click', '.edit-custom-image', function (e) {
        var $this = $(this);
        var id = $this.data('id');
        console.log('id', id);
    });
});
