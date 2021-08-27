document.addEventListener("DOMContentLoaded", function () {
    console.log('DOMContentLoaded');
    $(document).on('click', '.order-info-show', function (e) {
        var $this = $(this);
        Swal.fire({
            html: $this.attr('data-info')
        });
    });
});
