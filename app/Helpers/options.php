<?php

if (!function_exists('worktypes_options'))
{
    /**
     * Opciones Tipo de obras
     * @return array
     */
    function worktypes_options() {

        $options = [
            [
                'value' => 'Normal',
                'text' => 'Normal'
            ],
            [
                'value' => 'Adicional',
                'text' => 'Adicional'
            ],
            [
                'value' => 'Extraordinaria',
                'text' => 'Extraordinaria'
            ],
            [
                'value' => 'Mixta',
                'text' => 'Mixta'
            ]
        ];

        return $options;
    }
}

if (!function_exists('budgetypes_options'))
{
    /**
     * Opciones Tipo de presupuesto
     * @return array
     */
    function budgettypes_options() {

        $options = [
            [
                'value' => 'De contrato',
                'text' => 'De contrato'
            ],
            [
                'value' => 'Paramétrico',
                'text' => 'Paramétrico'
            ],
            [
                'value' => 'Definitivo',
                'text' => 'Definitivo'
            ]
        ];

        return $options;
    }
}

if (!function_exists('catalogstatus_options'))
{
    /**
     * Opciones Tipo de presupuesto
     *
     * @param $value valor de la opción que queremos regresar
     * @return mixed
     */
    function catalogstatus_options($value = null) {

        $options = [
            [
                'value' => '1',
                'text' => 'Abierto'
            ],
            [
                'value' => '2',
                'text' => 'Cerrado'
            ],
        ];

        if ($value != null) {

            $option = [];

            foreach ($options as $row) {
                if ($row['value'] == $value) {
                    $option[0] = $row;
                    break;
                }
            }

            return $option[0];
        }

        return $options;
    }
}

if (!function_exists('type_daily_options'))
{
    /**
     * Opciones Tipo de presupuesto
     * @return array
     */
    function type_daily_options() {

        $options = [
            [
                'value' => 'Autorización',
                'text' => 'Autorización'
            ],
            [
                'value' => 'Convocatoria',
                'text' => 'Convocatoria'
            ],
            [
                'value' => 'Daño',
                'text' => 'Daño'
            ],
            [
                'value' => 'Definición',
                'text' => 'Definición'
            ],
            [
                'value' => 'Entrega',
                'text' => 'Entrega'
            ],
            [
                'value' => 'Información',
                'text' => 'Información'
            ],
            [
                'value' => 'Instrucción',
                'text' => 'Instrucción'
            ],
            [
                'value' => 'Solicitud',
                'text' => 'Solicitud'
            ],
            [
                'value' => 'Lista Negra',
                'text' => 'Lista Negra'
            ],
            [
                'value' => 'Recomendación',
                'text' => 'Recomendación'
            ],
            [
                'value' => 'Otro',
                'text' => 'Otro'
            ]
        ];

        return $options;
    }
}

if (!function_exists('works_type_business_typeperson'))
{
    /**
     * Opciones Tipo de persona para Empresa
     * @return array
     */
    function works_type_business_typeperson() {

        $options = [
            [
                'value' => 'física',
                'text' => 'Física'
            ],
            [
                'value' => 'moral',
                'text' => 'Moral'
            ]
        ];

        return $options;
    }
}

if (!function_exists('works_type_business_sector'))
{
    /**
     * Opciones sector para Empresa
     * @return array
     */
    function works_type_business_sector() {

        $options = [
            [
                'value' => 'Privado',
                'text' => 'Privado'
            ],
            [
                'value' => 'Público',
                'text' => 'Público'
            ]
        ];

        return $options;
    }
}

if (!function_exists('agreementtypes_options'))
{
    /**
     * Opciones para tipo de acuerdo
     * @return array
     */
    function agreementtypes_options() {

        $options = [
            [
                'value' => 'Addendum',
                'text' => 'Addendum'
            ],
            [
                'value' => 'Contrato',
                'text' => 'Contrato'
            ],
            [
                'value' => 'Órden de compra',
                'text' => 'Órden de compra'
            ]
        ];

        return $options;
    }
}

if (!function_exists('assignmenttypes_options'))
{
    /**
     * Opciones para tipo de asignación
     * @return array
     */
    function assignmenttypes_options() {

        $options = [
            [
                'value' => 'Asignación directa',
                'text' => 'Asignación directa'
            ],
            [
                'value' => 'Concurso por invitación',
                'text' => 'Concurso por invitación'
            ]
        ];

        return $options;
    }
}

if (!function_exists('contractmodality_options'))
{
    /**
     * Opciones para modalidad de contrato
     * @return array
     */
    function contractmodality_options() {

        $options = [
            [
                'value' => 'Precios Unitarios',
                'text' => 'Precios Unitarios'
            ],
            [
                'value' => 'Precio Alzado',
                'text' => 'Precio Alzado'
            ]
        ];

        return $options;
    }
}

if (!function_exists('binnacle_note_type_options'))
{
    /**
     * Opciones para tipo de nota en bitácora
     * @return array
     */
    function binnacle_note_type_options() {

        $options = [
            [
                'value' => 'Original',
                'text' => 'Original'
            ],
            [
                'value' => 'Respuesta',
                'text' => 'Respuesta'
            ]
        ];

        return $options;
    }
}

if (!function_exists('binnacle_groups_options'))
{
    /**
     * Opciones para grupos en bitácora
     * @return array
     */
    function binnacle_groups_options() {

        $options = [
            [
                'value' => 'Órdenes',
                'text' => 'Órdenes'
            ],
            [
                'value' => 'Certificaciones',
                'text' => 'Certificaciones'
            ],
            [
                'value' => 'Autorizaciones',
                'text' => 'Autorizaciones'
            ],
            [
                'value' => 'Informaciones',
                'text' => 'Informaciones'
            ],
            [
                'value' => 'Prevenciones',
                'text' => 'Prevenciones'
            ],
            [
                'value' => 'Solicitudes',
                'text' => 'Solicitudes'
            ],
            [
                'value' => 'Aceptaciones',
                'text' => 'Aceptaciones'
            ],
            [
                'value' => 'Inconformidades',
                'text' => 'Inconformidades'
            ],
            [
                'value' => 'Exigencias',
                'text' => 'Exigencias'
            ],
            [
                'value' => 'Advertencias',
                'text' => 'Advertencias'
            ],
            [
                'value' => 'Otros',
                'text' => 'Otros'
            ]
        ];

        return $options;
    }
}

if (!function_exists('binnacle_destination_options'))
{
    /**
     * Opciones para grupos en bitácora
     * @return array
     */
    function binnacle_destination_options() {

        $options = [
            [
                'value' => 'a la contratista',
                'text' => 'a la contratista'
            ],
            [
                'value' => 'a la Supervisión',
                'text' => 'a la Supervisión'
            ],
            [
                'value' => 'a la Gerencia de Obra',
                'text' => 'a la Gerencia de Obra'
            ],
            [
                'value' => 'al arrendador',
                'text' => 'al arrendador'
            ],
            [
                'value' => 'al proveedor',
                'text' => 'al proveedor'
            ],
            [
                'value' => 'al proyectista',
                'text' => 'al proyectista'
            ],
            [
                'value' => 'al Cliente',
                'text' => 'al Cliente'
            ]
        ];

        return $options;
    }
}