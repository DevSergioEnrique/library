<?php
class Estudiantes extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        parent::__construct();
        $id_user = $_SESSION['id_usuario'];
        $perm = $this->model->verificarPermisos($id_user, "Estudiantes");
        if (!$perm && $id_user != 1) {
            $this->views->getView($this, "permisos");
            exit;
        }
    }
    public function index()
    {
        $this->views->getView($this, "index");
    }
    
    public function listar()
    {
        $data = $this->model->getEstudiantes();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-primary" type="button" onclick="btnEditarEst(' . $data[$i]['id'] . ');"><i class="fa fa-pencil-square-o"></i></button>
                <button class="btn btn-warning" type="button" onclick="btnEliminarEst(' . $data[$i]['id'] . ');"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                <button class="btn btn-danger" type="button" onclick="btnBorrarEst(' . $data[$i]['id'] . ');"><i class="fa fa-trash-o"></i></button>
                <div/>';
            } else {
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-success" type="button" onclick="btnReingresarEst(' . $data[$i]['id'] . ');"><i class="fa fa-reply-all"></i></button>
                <div/>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $codigo = strClean($_POST['codigo']);
        $dni = strClean($_POST['dni']);
        $nombre = strClean($_POST['nombre']);
        $a_paterno = strClean($_POST['a_paterno']);
        $a_materno = strClean($_POST['a_materno']);
        $grado = strClean($_POST['grado']);
        $seccion = strClean($_POST['seccion']);
        $id = strClean($_POST['id']);
        if (empty($codigo) || empty($dni) || empty($nombre) || empty($a_paterno) || empty($a_materno) || empty($grado)|| empty($seccion)) {
            $msg = array('msg' => 'Todos los campos son requeridos', 'icono' => 'warning');
        } else {
            if ($id == "") {
                    $data = $this->model->insertarEstudiante($codigo, $dni, $nombre, $a_paterno, $a_materno, $grado, $seccion);
                    if ($data == "ok") {
                        $msg = array('msg' => 'Estudiante registrado', 'icono' => 'success');
                    } else if ($data == "existe") {
                        $msg = array('msg' => 'El estudiante ya existe', 'icono' => 'warning');
                    } else {
                        $msg = array('msg' => 'Error al registrar', 'icono' => 'error');
                    }
            } else {
                $data = $this->model->actualizarEstudiante($codigo, $dni, $nombre, $a_paterno, $a_materno, $grado, $seccion, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Estudiante modificado', 'icono' => 'success');
                } else {
                    $msg = array('msg' => 'Error al modificar', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar($id)
    {
        $data = $this->model->editEstudiante($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar($id)
    {
        $data = $this->model->estadoEstudiante(0, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Estudiante dado de baja', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al eliminar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function borrar($id)
    {
        $data = $this->model->eliminarEstudiante($id);
        if ($data == 1) {
            $this->model->reiniciarAutoIncrement();
            $msg = array('msg' => 'Estudiante eliminado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al eliminar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function reingresar($id)
    {
        $data = $this->model->estadoEstudiante(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Estudiante restaurado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al restaurar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function buscarEstudiante()
    {
        if (isset($_GET['est'])) {
            $valor = $_GET['est'];
            $data = $this->model->buscarEstudiante($valor);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
}
