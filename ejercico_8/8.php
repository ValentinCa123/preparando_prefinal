<?php

// Agregar un álbum al sistema.

// - Se debe controlar que el usuario esté logueado al sistema.
// - Se deben controlar posibles errores de carga.
// - Se debe controlar que no exista un álbum con el mismo nombre.
// - SI el álbum pertenece a un artista premium, 
// se debe insertar automáticamente una valoración de 5 estrellas para ese álbum.   

// ARTISTA(id: int, nombre: string, premium: boolean)

// ALBUM(id: int, titulo: string, productor: string, genero: string,
// fechaLanzamiento: string, id_artista: int)

// VALORACION(id: int, estrellas: int, id_album: int,id_user: int)

class albumController{
    
    private $albumModel;
    private $view;
    private $valoracionesModel;
    private $authHelper;

    function __construct()
    {
        $this->albumModel= new albumModel();
        $this->valoracionesModel= new valoracionesModel();
        $this->view = new albumView();
        $this->authHelper = new AuthHelper();
    }

    // CORRECIONES
//    Esto porque sale de un post?
//    if($_POST['premium'] == 1){

//     Falto: - Se debe controlar que no exista un álbum con el mismo nombre.

    function insertAlbum(){
        //checkeo que el usuario este logueado
        $this->helper->checkLogin();
        //checkeo que esten llegando todos los datos del form
        if(!isset($_POST['titulo']) || !isset($_POST['productor']) || !isset($_POST['genero']) || !isset($_POST['fechaLanzamiento']) || !isset($_POST['id_artista'])){
            $this->view->mostrarError("Error al insertar el album");
        }else{
            //voy a buscar el album por el titulo que el usuario puso
            $album = $this->albumModel->getAlbumByTitulo($_POST['titulo']);
            //si el album existe no lo inserto e informo
            if($album){
                $this->view->mostrarError("Ya existe un album con ese titulo");
            }else{
                //si no existe lo inserto y accedo a los datos 
                $titulo = $_POST['titulo'];
                $productor = $_POST['productor'];
                $genero = $_POST['genero'];
                $fechaLanzamiento = $_POST['fechaLanzamiento']; 
                $id_artista = $_POST['id_artista'];
                //inserto el album y asumo que el id es auto incremental
                $this->albumModel->insertAlbum($titulo, $productor, $genero, $fechaLanzamiento, $id_artista);
                $this->view->mostrarExito("Album insertado correctamente");
                //voy a buscar el artista por su id
                $artista = $this->albumModel->getArtistaById($album->id_artista);
                //si el artista es premium le agrego una valoracion de 5 estrellas
                if($artista->premium == true){
                    $this->valoracionesModel->insertarValoracion(5, $album->id, $_SESSION['id']);
                    $this->view->mostrarExito("Valoracion insertada correctamente");
                }
            }
        }
    }
}

class albumModel{
    private $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ejercicio_8;charset=utf8', 'root', '');
    }

    function getAlbumByTitulo($titulo){
        $query = $this->db->prepare("SELECT * FROM album WHERE titulo=?");
        $query->execute(array($titulo));
        $album = $query->fetch(PDO::FETCH_OBJ);
        return $album;
    }

    function insertAlbum( $titulo, $productor, $genero, $fechaLanzamiento, $id_artista){
        $query = $this->db->prepare("INSERT INTO album (titulo, productor, genero, fechaLanzamiento, id_artista) VALUES (?,?,?,?,?)");
        $query->execute(array($titulo, $productor, $genero, $fechaLanzamiento, $id_artista));
    }
}

class artistasModel{
    private $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ejercicio_8;charset=utf8', 'root', '');
    }

    function getArtistaById($id){
        $query = $this->db->prepare("SELECT * FROM artista WHERE id=?");
        $query->execute(array($id));
        $artista = $query->fetch(PDO::FETCH_OBJ);
        return $artista;
    }
}

class valoracionModel(){
    private $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ejercicio_8;charset=utf8', 'root', '');
    }

    function insertarValoracion($estrellas, $id_album, $id_user){
        $query = $this->db->prepare("INSERT INTO valoracion (estrellas, id_album, id_user) VALUES (?,?,?)");
        $query->execute(array($estrellas, $id_album, $id_user));
    }
}