<?php
/**
 * GenesisPHP - AdmBitacora ListadoCSV
 *
 * Copyright (C) 2016 Guillermo Valdés Lozano
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package GenesisPHP
 */

namespace AdmBitacora;

/**
 * Clase ListadoCSV
 */
class ListadoCSV extends Listado {

    // protected $sesion;
    // public $listado;
    // public $panal;
    // public $cantidad_registros;
    // public $limit;
    // public $offset;
    // protected $consultado;
    // public $usuario;
    // public $usuario_nombre;
    // public $tipo;
    // public $fecha_desde;
    // public $fecha_hasta;
    // static public $param_usuario;
    // static public $param_tipo;
    // static public $param_fecha_desde;
    // static public $param_fecha_hasta;
    // public $filtros_param;
    protected $estructura;
    const RAIZ_CSV_ARCHIVO = 'admbitacora.csv';

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        // Filtros que puede recibir por el URL
        $this->usuario     = $_GET[parent::$param_usuario];
        $this->tipo        = $_GET[parent::$param_tipo];
        $this->fecha_desde = $_GET[parent::$param_fecha_desde];
        $this->fecha_hasta = $_GET[parent::$param_fecha_hasta];
        // Estructura
        $this->estructura = array(
            'fecha'             => array('enca' => 'Fecha'),
            'usuario_nom_corto' => array('enca' => 'Usuario'),
            'pagina'            => array('enca' => 'Página'),
            'tipo'              => array('enca' => 'Tipo', 'cambiar' => Registro::$tipo_descripciones),
            'notas'             => array('enca' => 'Notas')
        );
        // Ejecutar el constructor del padre
        parent::__construct($in_sesion);
    } // constructor

    /**
     * CSV
     *
     * @return string CSV
     */
    public function csv() {
        // Consultar si no se ha hecho
        if (!$this->consultado) {
            $this->consultar();
        }
        // Iniciar listado csvniciar listado csv
        $listado_csv             = new \Base2\ListadoCSV();
        $listado_csv->estructura = $this->estructura;
        $listado_csv->listado    = $this->listado;
        // Entregar
        return $listado_csv->csv();
    } // csv

} // Clase ListadoCSV

?>
