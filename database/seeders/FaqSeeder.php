<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'Nombre' => 'General',
                'Icono' => '🏢',
                'faqs' => [
                    [
                        'Pregunta' => '¿Qué es TaskLink?',
                        'Respuesta' => 'TaskLink es una plataforma que conecta a personas que necesitan ayuda con tareas o servicios específicos con profesionales o "Taskers" capacitados para realizarlos.'
                    ],
                    [
                        'Pregunta' => '¿Es seguro usar TaskLink?',
                        'Respuesta' => 'Sí, la seguridad es nuestra prioridad. Verificamos a los proveedores y contamos con un sistema de valoraciones para garantizar la confianza en la comunidad.'
                    ]
                ]
            ],
            [
                'Nombre' => 'Mi Cuenta',
                'Icono' => '👤',
                'faqs' => [
                    [
                        'Pregunta' => '¿Cómo cambio mi contraseña?',
                        'Respuesta' => 'Puedes cambiar tu contraseña desde la sección "Editar Perfil" en tu panel de usuario.'
                    ],
                    [
                        'Pregunta' => '¿Cómo puedo eliminar mi cuenta?',
                        'Respuesta' => 'Para eliminar tu cuenta, por favor contacta con nuestro equipo de soporte a través del formulario al final de esta página.'
                    ]
                ]
            ],
            [
                'Nombre' => 'Servicios',
                'Icono' => '🛠️',
                'faqs' => [
                    [
                        'Pregunta' => '¿Cómo publico un servicio?',
                        'Respuesta' => 'Si tienes el rol de proveedor, verás una opción de "Crear Servicio" en tu perfil. Completa los detalles, sube fotos y ¡listo!'
                    ],
                    [
                        'Pregunta' => '¿Qué tipo de servicios puedo ofrecer?',
                        'Respuesta' => 'Puedes ofrecer cualquier servicio legal, desde limpieza y reformas hasta consultoría técnica o clases particulares.'
                    ]
                ]
            ],
            [
                'Nombre' => 'Pagos y Reservas',
                'Icono' => '💳',
                'faqs' => [
                    [
                        'Pregunta' => '¿Cómo reservo un servicio?',
                        'Respuesta' => 'Entra en el servicio que te interesa, selecciona una fecha y hora disponible desde el calendario y confirma la reserva.'
                    ],
                    [
                        'Pregunta' => '¿Qué pasa si necesito cancelar?',
                        'Respuesta' => 'Puedes cancelar tus reservas desde la sección "Mis Reservas". Ten en cuenta las políticas de cancelación de cada proveedor.'
                    ]
                ]
            ]
        ];

        foreach ($categories as $catData) {
            $faqs = $catData['faqs'];
            unset($catData['faqs']);
            
            $category = FaqCategory::create($catData);
            
            foreach ($faqs as $faqData) {
                $faqData['idFaqCategoria'] = $category->IDFaqCategoria;
                Faq::create($faqData);
            }
        }
    }
}
