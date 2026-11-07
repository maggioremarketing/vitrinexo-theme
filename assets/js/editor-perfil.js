/* Vitrinexo — editor-perfil.js
   Sube la foto de perfil con recorte cuadrado interactivo (arrastrar + zoom) y barra de progreso.
   Los logos/banners de empresa usan handlers inline del shortcode PHP.
   Diseño seguro: si el cropper no carga o falla, se sube la imagen original (comportamiento previo).
*/
(function () {
  'use strict';

  var CROPPER_CSS = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css';
  var CROPPER_JS  = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js';
  var _cropperLoading = null;

  // Carga Cropper.js bajo demanda (una sola vez). Resuelve cuando window.Cropper existe.
  function ensureCropper() {
    if ( window.Cropper ) return Promise.resolve();
    if ( _cropperLoading ) return _cropperLoading;
    _cropperLoading = new Promise( function ( resolve, reject ) {
      try {
        if ( ! document.querySelector( 'link[data-vx-cropper]' ) ) {
          var link = document.createElement( 'link' );
          link.rel = 'stylesheet'; link.href = CROPPER_CSS; link.setAttribute( 'data-vx-cropper', '1' );
          document.head.appendChild( link );
        }
        var s = document.createElement( 'script' );
        s.src = CROPPER_JS; s.async = true;
        s.onload = function () { resolve(); };
        s.onerror = function () { reject( new Error( 'cropper-load-failed' ) ); };
        document.head.appendChild( s );
        // Salvaguarda: si no carga en 8s, rechazar para caer al fallback.
        setTimeout( function () { if ( ! window.Cropper ) reject( new Error( 'cropper-timeout' ) ); }, 8000 );
      } catch ( e ) { reject( e ); }
    } );
    return _cropperLoading;
  }

  // Abre un modal de recorte cuadrado. Resuelve con un Blob (webp 600x600).
  // Rechaza con { reason:'cancel' } si el usuario cancela, o Error si algo falla.
  function vxCropSquare( file ) {
    return new Promise( function ( resolve, reject ) {
      ensureCropper().then( function () {
        var url = URL.createObjectURL( file );

        var overlay = document.createElement( 'div' );
        overlay.className = 'vx-crop-overlay';
        overlay.style.cssText = 'position:fixed;inset:0;z-index:100000;background:color-mix(in srgb, var(--tinta) 75%, transparent);display:flex;align-items:center;justify-content:center;padding:16px';

        var box = document.createElement( 'div' );
        box.style.cssText = 'background:var(--blanco);border-radius:14px;max-width:460px;width:100%;padding:18px;box-shadow:0 20px 60px color-mix(in srgb, var(--tinta) 35%, transparent)';
        box.innerHTML =
          '<h3 style="margin:0 0 4px;font-size:17px;font-weight:700">Recorta tu imagen</h3>' +
          '<p style="margin:0 0 12px;font-size:13px;color:var(--tinta-2)">Arrastra para mover y usa la rueda o el control para acercar. Se guardará cuadrada.</p>' +
          '<div style="max-height:52vh;background:var(--hueso);border-radius:10px;overflow:hidden"><img id="vx-crop-img" style="max-width:100%;display:block"></div>' +
          '<div style="display:flex;align-items:center;gap:8px;margin:12px 0 4px"><span style="font-size:12px;color:var(--tinta-2)">Zoom</span>' +
          '<input id="vx-crop-zoom" type="range" min="0" max="1" step="0.01" value="0" style="flex:1"></div>' +
          '<div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px">' +
          '<button type="button" id="vx-crop-cancel" class="btn-vx btn-ghost-vx btn-vx-sm">Cancelar</button>' +
          '<button type="button" id="vx-crop-ok" class="btn-vx btn-primary-vx btn-vx-sm"><i class="ti ti-crop me-1"></i>Recortar y subir</button>' +
          '</div>';

        overlay.appendChild( box );
        document.body.appendChild( overlay );

        var imgEl  = box.querySelector( '#vx-crop-img' );
        var zoomEl = box.querySelector( '#vx-crop-zoom' );
        var cropper = null;
        var settled = false;

        function cleanup() {
          try { if ( cropper ) cropper.destroy(); } catch ( e ) {}
          try { URL.revokeObjectURL( url ); } catch ( e ) {}
          if ( overlay.parentNode ) overlay.parentNode.removeChild( overlay );
        }
        function doCancel() { if ( settled ) return; settled = true; cleanup(); reject( { reason: 'cancel' } ); }
        function doFail( e ) { if ( settled ) return; settled = true; cleanup(); reject( e || new Error( 'crop-failed' ) ); }

        imgEl.onload = function () {
          try {
            cropper = new Cropper( imgEl, {
              aspectRatio: 1, viewMode: 1, dragMode: 'move', autoCropArea: 1,
              background: false, movable: true, zoomable: true, cropBoxMovable: false,
              cropBoxResizable: false, toggleDragModeOnDblclick: false, guides: false, center: false
            } );
          } catch ( e ) { doFail( e ); }
        };
        imgEl.onerror = function () { doFail( new Error( 'img-load-failed' ) ); };
        imgEl.src = url;

        zoomEl.addEventListener( 'input', function () {
          if ( ! cropper ) return;
          try {
            var data = cropper.getImageData();
            var min  = data.naturalWidth ? ( data.width / data.naturalWidth ) : 1;
            cropper.zoomTo( min * ( 1 + parseFloat( zoomEl.value ) * 2.5 ) );
          } catch ( e ) {}
        } );

        box.querySelector( '#vx-crop-cancel' ).addEventListener( 'click', doCancel );
        overlay.addEventListener( 'click', function ( e ) { if ( e.target === overlay ) doCancel(); } );

        box.querySelector( '#vx-crop-ok' ).addEventListener( 'click', function () {
          if ( ! cropper || settled ) return;
          try {
            var canvas = cropper.getCroppedCanvas( { width: 600, height: 600, imageSmoothingQuality: 'high' } );
            if ( ! canvas ) return doFail( new Error( 'no-canvas' ) );
            canvas.toBlob( function ( blob ) {
              if ( ! blob ) return doFail( new Error( 'no-blob' ) );
              settled = true; cleanup(); resolve( blob );
            }, 'image/webp', 0.9 );
          } catch ( e ) { doFail( e ); }
        } );
      } ).catch( function ( e ) { reject( e ); } );
    } );
  }

  // Exponer para los handlers inline de logo (empresa) del shortcode.
  window.vxCropSquare = vxCropSquare;

  // ── Foto de usuario (tipo='foto') ─────────────────────────────────────────────

  document.querySelectorAll( 'input[data-upload-type="foto"]' ).forEach( function ( input ) {
    if ( input.dataset.uploadInitialized ) return;
    input.dataset.uploadInitialized = '1';

    var container  = input.closest( '[data-upload-container]' ) || input.parentElement;
    var progressEl = container ? container.querySelector( '.vx-upload-progress' ) : null;
    var previewEl  = document.getElementById( 'vx-foto-preview' );

    function setPreview( src ) {
      if ( ! previewEl ) return;
      var img = previewEl.querySelector( 'img' );
      if ( ! img ) { img = document.createElement( 'img' ); previewEl.appendChild( img ); }
      img.src = src;
      img.style.cssText = 'width:60px;height:60px;border-radius:var(--radius-sm);object-fit:cover;border:2px solid var(--color-border)';
      var placeholder = previewEl.querySelector( 'i' );
      if ( placeholder ) placeholder.style.display = 'none';
    }

    function revertPreview() {
      if ( ! previewEl ) return;
      var img = previewEl.querySelector( 'img' );
      if ( img ) img.src = '';
      var placeholder = previewEl.querySelector( 'i' );
      if ( placeholder ) placeholder.style.display = '';
    }

    function doUpload( fileOrBlob ) {
      if ( typeof window.vxUploadXHR !== 'function' ) { console.warn( 'vxUploadXHR no disponible todavía' ); return; }
      window.vxUploadXHR(
        fileOrBlob, 'foto', null, progressEl,
        function ( json ) { /* éxito: el servidor ya guardó vx_foto */ },
        function ( msg ) {
          if ( typeof vxShowError === 'function' ) vxShowError( msg ); else alert( msg );
          revertPreview();
        }
      );
    }

    input.addEventListener( 'change', function () {
      var file = input.files[0];
      if ( ! file ) return;

      vxCropSquare( file ).then( function ( blob ) {
        var cropped = new File( [ blob ], 'foto.webp', { type: 'image/webp' } );
        try { setPreview( URL.createObjectURL( blob ) ); } catch ( e ) {}
        input.value = '';
        doUpload( cropped );
      } ).catch( function ( err ) {
        if ( err && err.reason === 'cancel' ) { input.value = ''; return; }
        // Fallback: subir la imagen original (comportamiento previo).
        try {
          var reader = new FileReader();
          reader.onload = function ( e ) { setPreview( e.target.result ); };
          reader.readAsDataURL( file );
        } catch ( e ) {}
        doUpload( file );
      } );
    } );
  } );

  // ── Propuesta de perfil (admin → usuario) ──────────────────────────────────

  window.vxResolverPropuesta = function ( accion ) {
    var banner = document.getElementById( 'vx-propuesta-banner' );
    var botones = banner ? banner.querySelectorAll( 'button' ) : [];
    botones.forEach( function ( b ) { b.disabled = true; } );

    fetch( vx_data.api_url + 'perfil/propuesta/' + accion, {
      method: 'POST',
      headers: { 'X-WP-Nonce': vx_data.nonce },
    } )
      .then( function ( res ) { return res.json(); } )
      .then( function ( json ) {
        if ( json.success ) {
          window.location.reload();
        } else {
          botones.forEach( function ( b ) { b.disabled = false; } );
        }
      } )
      .catch( function () {
        botones.forEach( function ( b ) { b.disabled = false; } );
      } );
  };

} )();
