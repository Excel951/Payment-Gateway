document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("sortProducts").addEventListener("change", function () {
        if (document.getElementById("sortProducts").value != 'default') {
            document.getElementById("sortForm").submit();
        }
    })
})