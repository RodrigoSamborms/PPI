(function(){
    var form = document.getElementById('userForm');
    if (!form) return;
    form.addEventListener('submit', function(e){
        e.preventDefault();
        var email = document.getElementById('email').value.trim();
        var pass = document.getElementById('pass').value.trim();
        var role = document.getElementById('role').value;
        // Un solo if que comprueba si alguno está vacío o rol sigue en '0'
        if (email === '' || pass === '' || role === '' || role === '0') {
            alert('Faltan Campos por LLenar');
        } else {
            alert('Campos LLenos');
        }
    });
})();
