document.addEventListener("DOMContentLoaded", function() {
    var infoProduct = document.getElementById('inf');
    var reviewProduct = document.getElementById('review');
    var subInfo = document.getElementById('sub-info');
    var subReview = document.getElementById('sub-review');

    function info(){
        if (infoProduct && reviewProduct && subInfo && subReview) {
            infoProduct.style.display = "block";
            reviewProduct.style.display = "none";
            subInfo.classList.add('active');
            subReview.classList.remove('active');
        } else {
            console.error("Một trong các phần tử không tồn tại.");
        }
    }

    function review(){
        if (infoProduct && reviewProduct && subInfo && subReview) {
            reviewProduct.style.display = "block";
            infoProduct.style.display = "none";
            subReview.classList.add('active');
            subInfo.classList.remove('active');
        } else {
            console.error("Một trong các phần tử không tồn tại.");
        }
    }

    subInfo.addEventListener("click", info);
    subReview.addEventListener("click", review);
});
