document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('load')
        .addEventListener('click', load);
});

function load() {
    setTimeout(function() { window.location.href = 'gui.html'; }, 1500);
}
