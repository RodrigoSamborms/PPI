/* ocultar.js
   Encapsula la lógica para asociar los botones .action-btn
   a la acción de ocultar la fila correspondiente usando jQuery.
   Si jQuery no está disponible, carga la versión 3.3.1 desde CDN.
*/
(function(){
    function loadCDN(callback){
        var s = document.createElement('script');
        s.src = 'https://code.jquery.com/jquery-3.3.1.min.js';
        s.onload = callback;
        document.head.appendChild(s);
    }

    function attachHandlers($){
        // Usa la clase .action-btn ya presente en Tabla.html
        $(document).on('click', '.action-btn', function(e){
            var $tr = $(this).closest('tr');
            // Oculta con jQuery .hide(). Se puede cambiar por .fadeOut() si se desea animación.
            $tr.hide();
            $tr.attr('aria-hidden', 'true');
        });
    }

    function init(){
        if(window.jQuery){
            attachHandlers(window.jQuery);
        } else {
            loadCDN(function(){ attachHandlers(window.jQuery); });
        }
    }

    if(document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
