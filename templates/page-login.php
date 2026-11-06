<?php
/*
 * Template Name: Login
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( is_user_logged_in() ) {
    wp_safe_redirect( home_url( '/dashboard/' ) );
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class( 'vx-page-login page-auth-vx' ); ?>>

<nav class="navbar bg-white border-bottom px-4 py-2">
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand d-flex align-items-center gap-2">
    <img src="<?php echo esc_url( vx_logo_url() ); ?>" width="104" alt="Vitrinexo" class="flex-shrink-0">
  </a>
</nav>

<main>
  <?php the_content(); ?>
</main>

<?php get_template_part( 'partials/footer' ); ?>


<script>
(function () {
  // Tab switching
  document.querySelectorAll('.vx-tab[data-tab]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const tab = btn.dataset.tab;
      document.querySelectorAll('.vx-tab').forEach(t => t.classList.remove('vx-tab--active'));
      document.querySelectorAll('.vx-tab-panel').forEach(p => p.classList.remove('vx-tab-panel--active'));
      btn.classList.add('vx-tab--active');
      const panel = document.querySelector('.vx-tab-panel[data-panel="' + tab + '"]');
      if (panel) panel.classList.add('vx-tab-panel--active');
    });
  });

  // Mostrar/ocultar contraseña en login
  const pwdToggle = document.getElementById('login-pwd-toggle');
  const pwdInput  = document.getElementById('login-password');
  const pwdIcon   = document.getElementById('login-pwd-icon');
  if (pwdToggle && pwdInput) {
    pwdToggle.addEventListener('click', function () {
      const show = pwdInput.type === 'password';
      pwdInput.type = show ? 'text' : 'password';
      if (pwdIcon) pwdIcon.className = show ? 'ti ti-eye-off' : 'ti ti-eye';
    });
  }

  // Login
  const loginForm = document.getElementById('vx-login-form');
  if (loginForm) {
    loginForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const btn = this.querySelector('[type="submit"]');
      const errDiv = document.getElementById('vx-login-error');
      btn.disabled = true;
      btn.textContent = 'Iniciando sesión...';
      errDiv.classList.add('d-none');

      const data = new FormData(this);
      try {
        const res = await fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
          method: 'POST',
          body: new URLSearchParams({
            action:   'vx_ajax_login',
            nonce:    '<?php echo wp_create_nonce( "vx_ajax_login" ); ?>',
            email:    data.get('email'),
            password: data.get('password'),
            remember: document.getElementById('login-remember')?.checked ? '1' : '0',
          }),
        });
        const json = await res.json();
        if (json.success) {
          window.location.href = json.data.redirect || '<?php echo esc_js( home_url( '/dashboard/' ) ); ?>';
        } else {
          const msg = json.data?.message || 'Credenciales incorrectas.';
          errDiv.textContent = msg;
          errDiv.classList.remove('d-none');
          // Si está bloqueado, deshabilitar el botón permanentemente hasta recargar
          if (json.data?.locked) {
            btn.textContent = 'Acceso bloqueado, espera unos minutos';
            // No re-enable button
          } else {
            btn.disabled = false;
            btn.textContent = 'Iniciar sesión';
          }
        }
      } catch {
        errDiv.textContent = 'Error de conexión. Intenta de nuevo.';
        errDiv.classList.remove('d-none');
        btn.disabled = false;
        btn.textContent = 'Iniciar sesión';
      }
    });
  }

  // Registro
  const regForm = document.getElementById('vx-registro-form');
  if (regForm) {
    regForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const btn = this.querySelector('[type="submit"]');
      const errDiv = document.getElementById('vx-registro-error');

      // Validar checkbox de términos
      const terminos = document.getElementById('reg-terminos');
      if (terminos && !terminos.checked) {
        errDiv.textContent = 'Debes aceptar los Términos y Condiciones para continuar.';
        errDiv.classList.remove('d-none');
        return;
      }

      // Validar teléfono obligatorio
      const telefonoEl = document.getElementById('reg-telefono');
      if (!telefonoEl || !telefonoEl.value.trim()) {
        errDiv.textContent = 'El teléfono es obligatorio. Incluye el prefijo de tu país.';
        errDiv.classList.remove('d-none');
        if (telefonoEl) { telefonoEl.focus(); telefonoEl.style.borderColor = 'var(--rosa)'; }
        return;
      }

      btn.disabled = true;
      btn.textContent = 'Creando cuenta...';
      errDiv.classList.add('d-none');

      const data = new FormData(this);
      const body = {
        nombre:    data.get('nombre'),
        apellido:  data.get('apellido'),
        email:     data.get('email'),
        password:  data.get('password'),
        pais:      data.get('pais') || '',
        empresa:   data.get('empresa') || '',
        telefono:  data.get('telefono') || '',
      };

      try {
        const res = await fetch(vx_data.api_url + 'registrar', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': vx_data.nonce,
          },
          body: JSON.stringify(body),
        });
        const json = await res.json();
        if (json.success) {
          window.location.href = json.redirect || '<?php echo esc_js( home_url( '/confirmar-correo/' ) ); ?>';
        } else {
          const msgs = {
            email_invalido: 'El correo no es válido.',
            email_en_uso:   'Este correo ya está registrado.',
            password_muy_corta: 'La contraseña debe tener al menos 8 caracteres.',
          };
          errDiv.textContent = msgs[json.error] || 'Error al crear la cuenta.';
          errDiv.classList.remove('d-none');
          btn.disabled = false;
          btn.textContent = 'Crear cuenta';
        }
      } catch {
        errDiv.textContent = 'Error de conexión. Intenta de nuevo.';
        errDiv.classList.remove('d-none');
        btn.disabled = false;
        btn.textContent = 'Crear cuenta';
      }
    });
  }
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
