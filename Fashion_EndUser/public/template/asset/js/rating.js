function updateCharCount(productId) {
    var textarea = document.getElementById('comment-' + productId);
    var charCount = textarea.value.length;
    var charCountDisplay = document.getElementById('char-count-' + productId);
    charCountDisplay.textContent = `( ${charCount}/250 )`;
}


document.addEventListener('DOMContentLoaded', function() {
const ratings = document.querySelectorAll('.rating');

ratings.forEach(rating => {
    const stars = rating.querySelectorAll('.star');
    const input = document.querySelector(`#rating-value-${rating.id.split('-')[1]}`);
    
    stars.forEach(star => {
        star.addEventListener('mouseover', () => {
            const value = star.getAttribute('data-value');
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.classList.add('hovered');
                } else {
                    s.classList.remove('hovered');
                }
            });
        });

        star.addEventListener('mouseout', () => {
            const value = input.value;
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.classList.add('selected');
                } else {
                    s.classList.remove('selected');
                }
            });
            stars.forEach(s => s.classList.remove('hovered'));
        });

        star.addEventListener('click', () => {
            const value = star.getAttribute('data-value');
            input.value = value;
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.classList.add('selected');
                } else {
                    s.classList.remove('selected');
                }
            });
        });

        const initialValue = input.value;
        stars.forEach(s => {
            if (s.getAttribute('data-value') <= initialValue) {
                s.classList.add('selected');
            } else {
                s.classList.remove('selected');
            }
        });
    });
});
});
