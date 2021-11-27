<?php
// Mostrar todas las valoraciones realizadas a un álbum.
// - Se deben controlar posibles errores.

// BASE DE DATOS
// ARTISTA(id: int, nombre: string, premium: boolean)

// ALBUM(id: int, titulo: string, productor: string, genero: string, 
// fechaLanzamiento: string, id_artista: int)

// VALORACION(id: int, estrellas: int, id_album: int,id_user: int)


// COSAS A CORREGIR 
//- Dice que el albumID llegar por get pero lo pasa por parametros
//-¿Que pasa si el album no tiene valoraciones?


class valoracionesController
{

    private $view;
    private $valoracionesModel;
    private $albumModel;

    public function __construct()
    {
        // $this->view = new View();
        $this->model = new valoracionesModel();
        $this->album = new albumModel();
    }

    //el id del album esta llegando por get (supongo que el id se manda por anchor y pasa por el router asignado por la catedra y llega por $params[1])
    public function showValoracionesDeUnAlbum($id_album)
    {
        //voy a buscar el album por su id a la base de datos
        $album = $this->albumModel->getAlbum($id_album);
        //checkeo que exista un album con ese id
        if (!isset($album)) {
            $this->view->error("No existe el album");
            //si existe voy a buscar las valoraciones de ese album
        } else {
            $valoraciones = $this->valoracionesModel->getValoracionesDeUnAlbum($id_album);
            //checkeo que existan valoraciones de ese album
            if (isset($valoraciones)) {
                $this->view->showValoracionesDeUnAlbum($album, $valoraciones);
                //si no existen le comunico a la vista
            } else {
                $this->view->error("No existen valoraciones para este album");
            }
        }
    }
}


class albumModel
{

    private $db;

    function __construct()
    {

        $this->db = new PDO('mysql:host=localhost;' . 'dbname=recu;charset=utf8', 'root', '');
    }

    function getAlbum($id_album)
    {
        $query = $this->db->prepare("SELECT * FROM album WHERE id_album=?");
        $query->execute(array($id_album));
        $album = $query->fetch(PDO::FETCH_OBJ);
        return $album;
    }
}


class valoracionesModel
{
    private $db;

    function __construct()
    {

        $this->db = new PDO('mysql:host=localhost;' . 'dbname=recu;charset=utf8', 'root', '');
    }

    function getValoraciones($id_album)
    {
        $query = $this->db->prepare("SELECT * FROM valoraciones WHERE id_album=?");
        $query->execute(array($id_album));
        $valoraciones = $query->fetchAll(PDO::FETCH_OBJ);
        return $valoraciones;
    }
}
