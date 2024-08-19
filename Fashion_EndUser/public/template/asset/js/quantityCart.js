function addItem() {
    var quantityElement = document.getElementById('form-display');
    var max_quantityElement = document.getElementById('max_quantity');
    var quantity = parseInt(quantityElement.value, 10);
    var maxQuantity = parseInt(max_quantityElement.value, 10);

    if (quantity < maxQuantity) {
        quantity++;
        quantityElement.value = quantity;
    } else {
        alert("Số lượng sản phẩm đã vượt quá số lượng trong kho!");
    }
}

function delItem() {
    var quantityElement = document.getElementById('form-display');
    var quantity = parseInt(quantityElement.value, 10);

    if (quantity > 1) {
        quantity--;
        quantityElement.value = quantity;
    }
}
