const axios = require('axios');
window.jBox = require('jbox');

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
        var name = $this.data('name');
        console.log('id', id);
        const box = new jBox('Modal', {
            width: '80vw',
            height: '80vh',
            title: 'Выбор изображения для товара ' + name,
            content: `<div id="select-products" data-product-id="${id}" data-product-name="${name}">Поиск картинок...</div>`,
        });
        searchImagesForProduct(name, id);
        box.open();
        document.addEventListener('imageSelected', function (e) {
            Swal.fire('Изображение установлено');
            box.close();
        })
    });

    $(document).on('click', '.select-button', function () {
        var $this = $(this);
        const productID = $this.data('product-id');
        const imageUrl = $this.data('image');
        console.log('clicked', $this.data('product-id'));
        var options = {
            method: 'POST',
            url: window.options.routes['admin.setImage'],
            data: {
                productID: $this.data('product-id'),
                imageUrl: $this.data('image'),
            },
        };
        axios.request(options)
            .then(function (response) {
                const event = new Event('imageSelected');
                document.dispatchEvent(event);
                $('.row-customimageexplorer[data-id=' + productID + '] img').attr('src', imageUrl);
            })
            .catch(function (error) {
                console.error(error);
            });
    });
});

function searchImagesForProduct(productName, productID) {
    var options = {
        method: 'GET',
        url: window.options.config.bing.url,
        params: {q: productName},
        headers: {
            'x-rapidapi-host': window.options.config.bing.host,
            'x-rapidapi-key': window.options.config.bing.key,
        }
    };
    axios.request(options).then(function (response) {
        $('#select-products').html('');
        for (var val in response.data.value) {
            var value = response.data.value[val];
            const img = $(`<div class="select-relative"><button data-product-id="${productID}" data-image="${value.contentUrl}" class=" btn btn-success select-button"><i class="fa fa-check"></i>Выбрать</button><img class="select-relative-img" data-id="${productID}" src="${value.contentUrl}"/></div>`)
            img.appendTo($('#select-products'));
        }
    }).catch(function (error) {
        console.error(error);
    });
}
