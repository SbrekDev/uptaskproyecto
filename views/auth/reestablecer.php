<div class="contenedor reestablecer">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nueva contraseña</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if($mostrar) : ?>

        <form class="formulario" method="POST">
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu nueva contraseña">
            </div>

            <input type="submit" class="boton" value="Guardar">
        </form>

        <?php endif; ?>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes cuenta? Creá una</a>
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
        </div>
    </div>
</div>