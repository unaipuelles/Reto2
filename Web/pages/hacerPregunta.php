<?php
require "../php/generar-nav-footer.php";

    if(session_id()==''){
        session_start();
    }

    if(!isset($_SESSION['userLogged']))
    {
        header('Location: login.php');
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Haz tu pregunta &#124; Aergibide S.L.</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/hacerPregunta.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/grid-general.css" />
    <link rel="stylesheet" type="text/css" href="../css/hacerPregunta.css" />
    <link rel="stylesheet" type="text/css" href="../css/form.css">
</head>
<body>
<main id="contenedor-principal">
    <?php
    generarNav("../");
    ?>
    <section id="contenedor-hacer-preguntas">
        <h1>Haz tu pregunta</h1>
        <form action="../php/hacerPregunta.php" method="post" onsubmit="return validarDatos();">
            <input type="text" name="tituloPregunta" class="transparent" value="" placeholder="T&iacute;tulo" />
            <textarea name="descripcionPregunta" class="transparent" placeholder="Descripci&oacute;n"></textarea>
            <input type="text" name="categoriasPregunta" class="transparent"  value="" placeholder="Categor&iacute;as" />
            <input type="submit" class="submit" value="Enviar" />
        </form>
    </section>
    <?php
    generarFooter("../");
    ?>
</main>
</body>
</html>