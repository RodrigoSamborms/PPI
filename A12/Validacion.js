// Validacion.js
// Contiene la función `recibe()` que valida los campos del formulario `forma01`.
// Si falta al menos un campo muestra alert("Faltan Campos Por LLenar").
// Si todos los campos están llenos muestra alert("Todos los Campos Estan LLenos").

function recibe() {
    var form = document.forms['forma01'];
    if (!form) {
        alert('Formulario no encontrado');
        return false;
    }

    // Tomamos valores y verificamos según el tipo
    var nombre = (form.nombre && form.nombre.value) ? form.nombre.value.trim() : '';
    var correo = (form.correo && form.correo.value) ? form.correo.value.trim() : '';

    // radios (sexo)
    var sexoElems = form.elements['sexo'];
    var sexoSeleccionado = false;
    if (sexoElems) {
        if (sexoElems.length === undefined) { // solo uno
            sexoSeleccionado = sexoElems.checked;
        } else {
            for (var i = 0; i < sexoElems.length; i++) {
                if (sexoElems[i].checked) { sexoSeleccionado = true; break; }
            }
        }
    }

    // checkbox boletin
    var boletinChecado = false;
    if (form.boletin) {
        boletinChecado = !!form.boletin.checked;
    }

    var comentario = (form.comentario && form.comentario.value) ? form.comentario.value.trim() : '';
    var carrera = (form.carrera && form.carrera.value) ? form.carrera.value : '';
    var pasw = (form.pasw && form.pasw.value) ? form.pasw.value.trim() : '';
    var promedio = (form.promedio && form.promedio.value) ? form.promedio.value : '';
    var fecha = (form.fecha && form.fecha.value) ? form.fecha.value : '';

    // Evaluación: consideramos "vacío" si cadena vacía, o en el caso de select si su valor es '0' (la opción Selecciona)
    var faltan = false;
    var primerCampoVacio = null;

    if (!nombre) { faltan = true; primerCampoVacio = primerCampoVacio || form.nombre; }
    if (!correo) { faltan = true; primerCampoVacio = primerCampoVacio || form.correo; }
    if (!sexoSeleccionado) { faltan = true; /* no hay elemento único para enfocar fácilmente */ }
    // checkbox: si deseas que sea obligatorio, se considera vacío cuando NO está checado
    if (!boletinChecado) { faltan = true; primerCampoVacio = primerCampoVacio || (form.boletin || null); }
    if (!comentario) { faltan = true; primerCampoVacio = primerCampoVacio || form.comentario; }
    if (!carrera || carrera === '0') { faltan = true; primerCampoVacio = primerCampoVacio || form.carrera; }
    if (!pasw) { faltan = true; primerCampoVacio = primerCampoVacio || form.pasw; }
    if (!promedio) { faltan = true; primerCampoVacio = primerCampoVacio || form.promedio; }
    if (!fecha) { faltan = true; primerCampoVacio = primerCampoVacio || form.fecha; }

    if (faltan) {
        alert('Faltan Campos Por LLenar');
        // Opcional: enfocar el primer campo vacío si existe y tiene focus
        try {
            if (primerCampoVacio && typeof primerCampoVacio.focus === 'function') {
                primerCampoVacio.focus();
            }
        } catch (e) { /* ignore focus errors */ }
        return false;
    } else {
        alert('Todos los Campos Estan LLenos');
        return true;
    }
}
