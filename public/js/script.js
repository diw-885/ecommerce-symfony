$(document).ready(function () {
    // Plus & Minus for Quantity product
    var quantity = 1;

    $('.quantity-right-plus').click(function (e) {
        e.preventDefault();
        quantity = parseInt($('#quantity').val());
        $('#quantity').val(quantity + 1);
    });

    $('.quantity-left-minus').click(function (e) {
        e.preventDefault();
        quantity = parseInt($('#quantity').val());

        if (quantity > 1) {
            $('#quantity').val(quantity - 1);
        }
    });

    // Filtres des produits
    // Je coche / décoche une case des filtres, je fais une requête AJAX sur Symfony
    // et je récupère donc les produits liés aux couleurs
    $('.form-check-input').change(function () {
        // Les couleurs sélectionnées dans le filtre
        var colors = $('#filters').serialize();

        // On peut exécuter une requête en AJAX sur Symfony
        $.get('/api/products', colors).then(function (response) {
            $('#product-list').html(response);
        });
        // S'affiche avant la response
        // alert('toto');
    });
});
