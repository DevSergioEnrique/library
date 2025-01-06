<?php
class EstudiantesModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }
    public function getEstudiantes()
    {
        $sql = "SELECT 
            e.id AS id,
            e.codigo AS codigo,
            e.dni AS dni,
            e.nombre AS nombre,
            e.a_paterno AS a_paterno,
            e.a_materno AS a_materno,
            g.grado AS grado,
            s.seccion AS seccion,
            e.estado AS estado
            FROM estudiante e 
            INNER JOIN grado g ON e.grado = g.id_Grado 
            INNER JOIN seccion s ON e.seccion = s.id_Seccion";
        $res = $this->selectAll($sql);
        return $res;
    }
    public function insertarEstudiante($codigo, $dni, $nombre, $a_paterno, $a_materno, $grado, $seccion)
    {
        $verificar = "SELECT * FROM estudiante WHERE codigo = '$codigo'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $query = "INSERT INTO estudiante(codigo,dni,nombre,a_paterno,a_materno, grado, seccion, estado) VALUES (?,?,?,?,?,?,?,1)";
            $datos = array($codigo, $dni, $nombre, $a_paterno, $a_materno, $grado, $seccion);
            $data = $this->save($query, $datos);
            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "error";
            }
        } else {
            $res = "existe";
        }
        return $res;
    }
    public function editEstudiante($id)
    {
        $sql = "SELECT * FROM estudiante WHERE id = $id";
        $res = $this->select($sql);
        return $res;
    }
    public function actualizarEstudiante($codigo, $dni, $nombre, $a_paterno, $a_materno, $grado, $seccion, $id)
    {
        $query = "UPDATE estudiante SET codigo = ?, dni = ?, nombre = ?, a_paterno = ?, a_materno = ?, grado = ?, seccion = ?  WHERE id = ?";
        $datos = array($codigo, $dni, $nombre, $a_paterno, $a_materno, $grado, $seccion, $id);
        $data = $this->save($query, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }
    public function estadoEstudiante($estado, $id)
    {
        $query = "UPDATE estudiante SET estado = ? WHERE id = ?";
        $datos = array($estado, $id);
        $data = $this->save($query, $datos);
        return $data;
    }

    public function eliminarEstudiante($id)
    {
        $query = "DELETE FROM estudiante WHERE id = ?";
        $datos = array($id);
        $data = $this->save($query, $datos);
        return $data;
    }

    public function buscarEstudiante($valor)
    {
        $sql = "SELECT 
                e.id, 
                e.codigo, 
                CONCAT(e.nombre,' ', e.a_paterno, ' ', e.a_materno) AS text 
                FROM estudiante e
                WHERE (e.codigo LIKE '%" . $valor . "%' OR CONCAT(e.nombre,' ', e.a_paterno, ' ', e.a_materno) LIKE '%" . $valor . "%') 
                AND estado = 1 
                LIMIT 10";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarPermisos($id_user, $permiso)
    {
        $tiene = false;
        $sql = "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'";
        $existe = $this->select($sql);
        if ($existe != null || $existe != "") {
            $tiene = true;
        }
        return $tiene;
    }

    public function reiniciarAutoIncrement()
    {
        $sqlMaxId = "SELECT MAX(id) AS max_id FROM estudiante";
        $resultado = $this->select($sqlMaxId);
    
        $maxId = isset($resultado['max_id']) && is_numeric($resultado['max_id']) ? (int)$resultado['max_id'] : 0;
    
        $autoIncrement = $maxId + 1;
        $datos = array($autoIncrement);
    
        $sqlAlter = "ALTER TABLE estudiante AUTO_INCREMENT = ?";
        $this->query($sqlAlter, $datos);
    }
}
