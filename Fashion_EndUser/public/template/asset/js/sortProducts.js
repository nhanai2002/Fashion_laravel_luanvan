document.addEventListener('DOMContentLoaded', function() {
    var checkboxes = document.querySelectorAll('.filter-checkbox');
    var orderbySelect = document.querySelector('.orderby');

    function updateFormAndSubmit() {
        var form = document.getElementById('form__check');
        var formData = new FormData(form);
        var params = new URLSearchParams();

        // Iterate through form data to include checked checkboxes and selected orderby
        for (var [key, value] of formData.entries()) {
            if (key !== '_token') {
                params.append(key, value);
            }
        }

        // Update the form action with the query parameters
        form.action = form.action.split('?')[0] + '?' + params.toString();

        form.submit();
    }

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateFormAndSubmit);
    });

    orderbySelect.addEventListener('change', updateFormAndSubmit);
});
