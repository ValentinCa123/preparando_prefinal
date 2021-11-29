<?php
// Buscar todos los álbumes que tengan una valoración promedio mayor o igual a cierto número de estrellas..
// - Se deben controlar posibles errores.

// - Se debe mostrar el artista de cada uno de los álbumes.

// ARTISTA(id: int, nombre: string, premium: boolean)

// ALBUM(id: int, titulo: string, productor: string, genero: string,
// fechaLanzamiento: string, id_artista: int)

// VALORACION(id: int, estrellas: int, id_album: int,id_user: int)

class albumController{


    private $albumModel;
    private $view;
    private $valoracionesModel;
    private $authHelper;

    function __construct(){
        $this->albumModel= new albumModel();
        $this->valoracionesModel= new valoracionesModel();
        $this->view = new albumView();
        $this->authHelper = new AuthHelper();
    }
// Buscar todos los álbumes que tengan una valoración promedio mayor o igual a cierto número de estrellas..
    function promedioDeValoracionPorAlbum(){
        $numeroDeEstrellas = $_POST['Cantestrellas'];

        $albumes = $this->db->valoracionModel->valoracionPromedio($numeroDeEstrellas);
        // - Se debe mostrar el artista de cada uno de los álbumes.

        $artistas =[];
        foreach($albumes as $album){
            $artista = $this->modelArtista->getArtista($album->id_artista);
            array_push($arista, $artistas);
        }

        return $this->view->mostrarAlbunesConPromedioIgualOMayor($albunes, $artistas);
    }
}

class valoracionModel{

    private $db;

    function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ejercicio_8;charset=utf8', 'root', '');
    }
// ALBUM(id: int, titulo: string, productor: string, genero: string,
// fechaLanzamiento: string, id_artista: int)
    function valoracionPromedio($numeroDeEstrellas){
        $query = $this->db->prepare("SELECT * FROM album a JOIN valoracion v ON a.id=v.id_album GROUP BY album, v.estrellas HAVING AVG(v.estrellas)>= ?");
        $query->execute(array($numeroDeEstrellas));
        $promedio = $query->fetchAll(PDO::FETCH_OBJ);
        return $promedio;
    }

  
}