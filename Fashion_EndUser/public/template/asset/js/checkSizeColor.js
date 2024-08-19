
document.addEventListener('DOMContentLoaded', function () {
    const sizeOptions = document.querySelectorAll('.size-radio');
    const colorOptions = document.querySelectorAll('.color-radio');

    function updateSizeOptions(selectedColorId) {
        sizeOptions.forEach(sizeOption => {
            const sizeId = sizeOption.value;
            const inStock = warehouseItems.some(item => item.color_id == selectedColorId && item.size_id == sizeId);
            sizeOption.disabled = !inStock;
            sizeOption.parentElement.classList.toggle('disabled', !inStock);

            // Bỏ chọn size nếu không còn trong kho và size này đang được chọn
            if (!inStock && sizeOption.checked) {
                sizeOption.checked = false;
            }
        });
    }

    function updateColorOptions(selectedSizeId) {
        colorOptions.forEach(colorOption => {
            const colorId = colorOption.value;
            const inStock = warehouseItems.some(item => item.size_id == selectedSizeId && item.color_id == colorId);
            colorOption.disabled = !inStock;
            colorOption.parentElement.classList.toggle('disabled', !inStock);

            // Bỏ chọn màu nếu không còn trong kho và màu này đang được chọn
            if (!inStock && colorOption.checked) {
                colorOption.checked = false;
            }
        });
    }

    sizeOptions.forEach(sizeOption => {
        sizeOption.addEventListener('change', function () {
            if (this.checked) {
                updateColorOptions(this.value);
            }
        });
    });

    colorOptions.forEach(colorOption => {
        colorOption.addEventListener('change', function () {
            if (this.checked) {
                updateSizeOptions(this.value);
            }
        });
    });
});




