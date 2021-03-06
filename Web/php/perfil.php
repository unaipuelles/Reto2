<?php

require_once "db/dbUtils.php";

session_start();

/**
 * Busca todas las preguntas iniciadas por el usuario e imprime un <article> por cada pregunta
 * @param $idUsuario
 */
function listaPreguntasUsuario($idUsuario) {

    $preguntas=buscarPreguntasRespuestasUsuario("Preguntas", $idUsuario);
    foreach($preguntas as $clave=>$valor)
    {
        $usuario = encontrarUsuario("no",$valor['Usuario_idUsuario']);
        if (isset($valor['votos'])) {
            $tempListaVotos = puntuacionPreguntas($valor['votos']);
        }else $tempListaVotos = 0;
        preguntaRespuestaUsuario($valor["idPregunta"], $valor['Usuario_idUsuario'], $usuario['nombreusu'], $valor["fecha"],$valor["titulo"],$valor["temas"],$tempListaVotos);
    }
}

/**
 * Busca todas las preguntas donde el usuario ha realizado una respuesta e imprime un <article> por cada pregunta
 * @param $idUsuario
 */
function listaRespuestasUsuario($idUsuario) {

    $preguntas=buscarPreguntasRespuestasUsuario("Respuestas", $idUsuario);
    foreach($preguntas as $clave=>$valor)
    {
        $usuario = encontrarUsuario("no",$valor['Usuario_idUsuario']);
        if (isset($valor['votos'])) {
            $tempListaVotos = puntuacionPreguntas($valor['votos']);
        }else $tempListaVotos = 0;
        preguntaRespuestaUsuario($valor["idPregunta"], $valor['Usuario_idUsuario'], $usuario['nombreusu'], $valor["fecha"],$valor["titulo"] ,$valor["temas"],$tempListaVotos);
    }
}

/**
 * El <article> donde imprime cada pregunta del perfil de usuario
 * @param $id
 * @param $idUsuario
 * @param $usuario
 * @param $fecha
 * @param $titulo
 * @param $temas
 * @param $votos
 */
function preguntaRespuestaUsuario($id, $idUsuario, $usuario, $fecha, $titulo,$temas,$votos) {
    ?>
    <article class="pregunta-perfil">
        <span class="informacion-usuario-fecha-pregunta">por <a href="perfil.php?usuario=<?=$idUsuario?>" class="link-perfil-usuario"><?=$usuario?></a> a <?=$fecha?></span>
        <h2 class="titulo-pregunta"><a href="pregunta.php?preguntaid=<?=$id?>"><?=$titulo?></a></h2>
        <div id="contenedor-categorias-pregunta">
            <?php
                foreach ($temas as $clave=>$valor){
                    ?>
                        <a href="../index.php?busquedaPreguntas=<?=$valor['nombre']?>"><label><?=$valor['nombre']?></label></a>
                    <?php
                }
            ?>

        </div>
        <div id="contenedor-likes-pregunta">
            <div class="contenedor-likes-preguntas">
                <span class="puntuacion-pregunta-index"><?=$votos?></span>
            </div>
        </div>
    </article>
    <?php
}

/** Función que cuenta los votos afirmativos y negativos para calcular la puntuación de la pregunta
 * @param $listaVotos
 * @return int
 */
function puntuacionPreguntas($listaVotos){
    $tempcontador = 0;
    if($listaVotos!=null) {
        foreach ($listaVotos as $item => $value) {
            if ($value['tipo'] == 1) {
                $tempcontador++;
            } else {
                $tempcontador--;
            }
        }
    }
    return $tempcontador;
}
?>