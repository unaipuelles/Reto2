<?php
require '../php/generar-nav-footer.php';
require_once '../php/db/dbUtils.php';

session_start();
//Vereficar si hay un usuario loggeado, si no se establece null
if(!isset($_SESSION['userLogged'])){
    $_SESSION['userLogged'] = null;
}

$correcto = false;
//Si se ha enviado el formulario
if(isset($_POST['nombreusu'])){
    if($_POST['descripcion'] === ""){
        $_POST['descripcion'] = null;
    }

    //Verificar si no se ha adjuntado un archivo
    if(!is_uploaded_file($_FILES['img']['tmp_name'])){
        $_POST['img'] = "/media/usersImg/user_default.png";
    }
    else{
        $file = pathinfo($_FILES['img']['name']);
        $extension = $file['extension'];

        //Se le establece el nombre de usuario a la imagen
        $newname = $_POST['nombreusu'].".".$extension;
        $target = '../media/usersImg/'.$newname;

        //La imagen se guarda en el directorio especificado
        move_uploaded_file( $_FILES['img']['tmp_name'], $target);
        $_POST['img'] = '/media/usersImg/'.$newname;
    }
    //Generamos un array con todos los datos y encriptamos la contraseña
    $datos = Array('nombreusu' => $_POST['nombreusu'], 'correo' => $_POST['correo'],
        'pass' => password_hash($_POST['passw'], PASSWORD_DEFAULT), 'desc' => $_POST['descripcion'],
        'img' => $_POST['img']);

    try{
        //Introducir los datos en base de datos
        $correcto = registrarUsuario($datos);
    }
    catch (Exception $e){
        ?>
        <script>alert("Correo ya registrado")</script>
        <?php
    }

    echo $correcto;
}

if($correcto){
    //Si se han introducido correctamente los datos se busca el usuario en base de datos y se guarda en sesión
    $_SESSION['userLogged'] = encontrarUsuario($datos['correo']);
}

//Si ya hay un usuario loggeadoredirección al index
if($_SESSION['userLogged'] !== null)
    header("location:../index.php");
else{
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Aergibide S.L</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="../media/favicon_software_beasts_color.png"/>
        <link rel="stylesheet" type="text/css" href="../css/grid-general.css">
        <link rel="stylesheet" type="text/css" href="../css/form.css">
        <link rel="stylesheet" type="text/css" href="../css/registro.css">
        <script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="../js/registro.js"></script>
    </head>
    <body>
    <main id="contenedor-principal" onload="">
        <?php generarNav("../"); ?>
        <div class="pos">
            <form method="post" action="registro.php" enctype='multipart/form-data'>
                <label class="registro" for="nombreusu">Nombre Usuario<span class="required">*</span></label>
                <input type="text" id="nombreusu" class="transparent registro" name="nombreusu" required>
                <label class="registro" for="correo">Correo<span class="required">*</span></label>
                <input type="email" id="correo" class="transparent registro" name="correo" required>
                <label class="registro" for="correoConf">Confirmar correo<span class="required">*</span></label>
                <input type="email" id="correoConf" class="transparent registro" name="correoConf" required>
                <label class="registro" for="passw">Contraseña<span class="required">*</span></label>
                <input type="password" id="passw" class="transparent registro" name="passw" required>
                <label class="registro" for="passwConf">Confirmar contraseña<span class="required">*</span></label>
                <input type="password" id="passwConf" class="transparent registro" name="passwConf" required>
                <label class="registro" for="descripcion">Descripción</label>
                <textarea id="descripcion" class="transparent registro" name="descripcion"></textarea>
                <label class="registro" for="img">Imagen de perfil</label>
                <input type="file" id="img" class="transparent registro" name="img">
                <input type="submit" class="submit" value="Registrar">
            </form>
        </div>
        <?php
        generarFooter("../"); ?>
    </main>
    </body>
    </html>
    <?php
}
?>