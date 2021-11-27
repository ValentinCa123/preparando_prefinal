<?php

// Valorar un álbum.
// - Se deben controlar posibles errores.

// - Chequear que el usuario no haya valorado el artista anteriormente.

// - Si el usuario ya lo valoró, se reemplaza si la nueva es menor.

// Nota: No es necesario hacer la acción que muestra el formulario de valoración.


// BASE DE DATOS
// ARTISTA(id: int, nombre: string, premium: boolean)

// ALBUM(id: int, titulo: string, productor: string, genero: string,
// fechaLanzamiento: string, id_artista: int)

// VALORACION(id: int, estrellas: int, id_album: int,id_user: int)

class albumController
{

    private $view;
    private $artistaModel;
    private $albumModel;

    public function __construct()
    {
        // $this->view = new View();
        $this->artistaModel = new artistaModel();
        $this->valoracionModel = new valoracionModel();
        $this->albumModel = new albumModel();
    }


    // La valoración se debe leer por $_POST
    // Se debe chequear que el album exista
    // Se debe informar el resultado al usuario
    // No se busca si ya se valoró ese artista

    // - Se deben controlar posibles errores.

    // - Chequear que el usuario no haya valorado el artista anteriormente.

    // - Si el usuario ya lo valoró, se reemplaza si la nueva es menor.

    function insertarValoracionDeUnAlbum()
    {
        // ARTISTA(id: int, nombre: string, premium: boolean)

        // ALBUM(id: int, titulo: string, productor: string, genero: string,
        // fechaLanzamiento: string, id_artista: int)

        // VALORACION(id: int, estrellas: int, id_album: int,id_user: int)
        session_start();
        $id_user = $_SESSION['id'];
        $id_artista = $_POST['id_artista'];
        $estrellas =  $_POST['estrellas'];
        $id_album = $_POST['id_album'];

        // Chequear que el usuario no haya valorado el artista anteriormente.
        $valoracion = $this->valoracionModel->getValoracion($id_user);
        $album = $this->albumModel->getAlbum($id_album);
        $artista = $this->artistaModel->getArtista($id_artista);
                  
    }
}

class valoracionModel
{

    private $db;

    function __construct()
    {

        $this->db = new PDO('mysql:host=localhost;' . 'dbname=recu;charset=utf8', 'root', '');
    }

    function getValoracion($id_user)
    {
        $query = $this->db->prepare("SELECT * FROM valoracion WHERE id_user=?");
        $query->execute(array($id_user));
        $valoraciones = $query->fetchAll(PDO::FETCH_OBJ);
        return $valoraciones;
    }
}
