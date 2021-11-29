<?php

// Buscar todos los artistas que tengan una cantidad de álbumes mayor o igual a cierto número.
// - Se deben controlar posibles errores.

// - Se debe mostrar el artista con su cantidad de álbumes.


// BASE DE DATOS
// ARTISTA(id: int, nombre: string, premium: boolean)

// ALBUM(id: int, titulo: string, productor: string, genero: string,
// fechaLanzamiento: string, id_artista: int)

// VALORACION(id: int, estrellas: int, id_album: int,id_user: int)

class artistasController
{

    private $view;
    private $artistaModel;
    private $albumModel;

    public function __construct()
    {
        // $this->view = new View();
        $this->artistaModel = new artistaModel();
        $this->albumModel = new albumModel();
    }

    // Correccion
    // Checkie que esten llegando todos los datos
    function showArtistas()
    {
        //supongo que 
        $numero = $_POST['numero'];
        $artistas = $this->artistaModel->getArtistas();
        if (!isset($artistas)) {
            $this->view->error("No hay artistas disponibles");
        } else {
            // si $i es < a la cantidad de artistas voy a repetir.
            for ($i = 0; $i < count($artistas); $i++) {
                //traigo la cantidad de albumes de cada artista
                $cantidadDeAlbumes = $this->albumModel->getCantidadDeAlbum($artistas[$i]->id);
                //si no encontraste nada en la consulta mostras error
                if (!isset($cantidadDeAlbumes)) {
                    $this->view->error("No hay albumes disponibles");
                } else {
                    //si encontraste y la cantidad de albumes el mayor igual a $numero, muestro los $artistas con su cantidad de albumes
                    if ($cantidadDeAlbumes >= $numero) {
                        $this->view->showArtistas($artistas[$i], $cantidadDeAlbumes);
                    }
                }
            }
        }
    }
}

class artistaModel
{

    private $db;

    function __construct()
    {

        $this->db = new PDO('mysql:host=localhost;' . 'dbname=recu;charset=utf8', 'root', '');
    }

    function getArtistas()
    {
        $query = $this->db->prepare("SELECT * FROM artista");
        $query->execute();
        $artistas =  $query->fetchAll(PDO::FETCH_OBJ);
        return $artistas;
    }
}

class albumModel
{
    private $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;' . 'dbname=recu;charset=utf8', 'root', '');
    }

    function getCantidadDeAlbum($id)
    {
        $query = $this->db->prepare("SELECT COUNT(*) FROM album WHERE id_artista=?");
        $query->execute(array($id));
        $albumes = $query->fetchAll(PDO::FETCH_OBJ);
        return $albumes;
    }
}
