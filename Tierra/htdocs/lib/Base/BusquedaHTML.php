<?php
/**
 * GenesisPHP - BusquedaHTML
 *
 * Copyright (C) 2015 Guillermo Valdés Lozano
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

namespace Base;

/**
 * Clase BusquedaHTML
 */
abstract class BusquedaHTML {

    public $hay_resultados  = false;   // Verdadero si la búsqueda arrojo resultados
    public $entrego_detalle = false;   // Verdadero si la búsqueda arrojo un resultado, para que se muestren los hijos debajo del detalle
    public $hay_mensaje     = false;   // Verdadero si la búsqueda no encontro resultados, para que la página active la lengüeta y se vea el mensaje
    public $resultado;                 // Instancia con el resultado si tiene éxito, puede ser un detalle o un listado
    protected $sesion;                 // Instancia de la sesión
    protected $consultado;             // Booleano
    protected $javascript   = array(); // Arreglo, Javascript a colocar al final

    /**
     * Constructor
     *
     * @param mixed Sesion
     */
    public function __construct(\Inicio\Sesion $in_sesion) {
        $this->sesion = $in_sesion;
    } // constructor

    /**
     * Validar
     */
    abstract protected function validar();

    /**
     * Formulario
     */
    abstract protected function formulario();

    /**
     * Recibir los valores del formulario
     */
    abstract protected function recibir_formulario();

    /**
     * Consultar
     */
    abstract protected function consultar();

    /**
     * Formulario HTML
     *
     * Al entregar resultado la búsqueda deben mostrarse dos pestañas
     * una con los resultados y otra con el formulario. Este método es
     * para lo segundo.
     *
     * @param  string Encabezado opcional
     * @return string HTML
     */
    public function formulario_html($in_encabezado='Buscar') {
        // Recibir el formulario carga las propiedades con los valores de la búsqueda ya realizada
        $this->recibir_formulario();
        // Entregar el formulario de búsqueda
        try {
            return $this->formulario($in_encabezado);
        } catch (\Exception $e) {
            $mensaje = new MensajeHTML($e->getMessage());
            return $mensaje->html('Error');
        }
    } // formulario_html

    /**
     * HTML
     *
     * @param  string Encabezado opcional
     * @return string HTML
     */
    public function html($in_encabezado='Buscar') {
        // Si se envió el formulario
        if ($this->recibir_formulario()) {
            try {
                // Validar
                $this->validar();
                // Consultar
                $this->resultado = $this->consultar(); // Instancia de DetalleHTML o ListadoHTML
                // Mostrar resultado de la busqueda
                $html                  = $this->resultado->html();
                $this->javascript[]    = $this->resultado->javascript();
                $this->entrego_detalle = true;
                return $html;
            } catch (BusquedaHTMLExceptionValidacion $e) {
                // Falló la validación, mostrar mensaje y el formulario de nuevo
                $this->hay_mensaje = true;
                $mensaje = new MensajeHTML($e->getMessage());
                return $mensaje->html('Validación').$this->formulario($in_encabezado);
            } catch (BusquedaHTMLExceptionVacio $e) {
                // La búsqueda no arrojó resultados, mostrar mensaje y el formulario de nuevo
                $this->hay_mensaje = true;
                $mensaje = new MensajeHTML('Aviso: La búsqueda no encontró resultados.');
                return $mensaje->html().$this->formulario($in_encabezado);
            } catch (\Exception $e) {
                // Error fatal, mostrar mensaje
                $this->hay_mensaje = true;
                $mensaje = new MensajeHTML($e->getMessage());
                return $mensaje->html('Error');
            }
        } else {
            try {
                // No se recibió, mostrar el formulario listo para buscar
                return $this->formulario($in_encabezado);
            } catch (\Exception $e) {
                // Error fatal, mostrar mensaje
                $mensaje = new MensajeHTML($e->getMessage());
                return $mensaje->html('Buscar');
            }
        }
    } // html

    /**
     * Javascript
     *
     * @return string Javascript, si no hay entrega falso
     */
    public function javascript() {
        if (is_array($this->javascript) && (count($this->javascript) > 0)) {
            $a = array();
            foreach ($this->javascript as $js) {
                if (is_string($js) && ($js != '')) {
                    $a[] = $js;
                }
            }
            if (count($a) > 0) {
                return implode("\n", $a);
            } else {
                return false;
            }
        } else {
            return false;
        }
    } // javascript

} // Clase BusquedaHTML

?>
