<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Question;
use App\Models\Option;
use App\Models\Area;
use App\Models\Factor;

class TestsSeeder extends Seeder
{
    public function run()
    {
        // Asegurarnos de que existan áreas y factores
        if (!Area::exists() || !Factor::exists()) {
            $this->call(AreaFactorSeeder::class);
        }

        $test = Test::create([
            'name' => 'Evaluación de Competencias Digitales Docentes',
            'description' => 'Test para evaluar las competencias digitales de los docentes según el marco DigCompEdu',
            'is_active' => true
        ]);

        // Obtener el primer área y factor para asignarlos a las preguntas
        $area = Area::first();
        $factor = Factor::first();

        if (!$area || !$factor) {
            throw new \Exception('No se encontraron áreas o factores. Por favor, ejecute primero el AreaFactorSeeder.');
        }

        $question1 = Question::create([
            'test_id' => $test->id,
            'question' => "Utilizo sistemáticamente diferentes canales de comunicación para mejorar la comunicación con estudiantes y colegas. Por ejemplo: correos electrónicos, blogs, sitio web de la institución, aplicaciones…",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 1
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Rara vez uso canales de comunicación digitales.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Utilizo canales de comunicación básicos, por ejemplo el correo electrónico.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Combino diferentes canales de comunicación, por ejemplo el correo electrónico, el blog de la clase o el sitio web de la institución.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Selecciono, ajusto y combino sistemáticamente diferentes soluciones digitales para comunicarme de manera efectiva.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Reflexiono, discuto y desarrollo mis estrategias de comunicación de forma proactiva.",
            'score' => 4,
        ]);


        $question2 = Question::create([
            'test_id' => $test->id,
            'question' => "Utilizo tecnologías digitales para trabajar con colegas dentro y fuera de mi institución.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 2
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Rara vez tengo la oportunidad de colaborar con otros colegas.",
            'score' =>0 ,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "A veces intercambio materiales con colegas, por ejemplo por correo electrónico.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Entre colegas, trabajamos juntos en entornos colaborativos o utilizamos archivos/unidades compartidos.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Intercambio ideas y materiales, también con colegas fuera de mi institución, por ejemplo en una red profesional en línea o en un espacio colaborativo en línea.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Creo materiales junto con otros colegas en una red online de profesionales de diferentes instituciones.",
            'score' => 4,
        ]);


        $question3 = Question::create([
            'test_id' => $test->id,
            'question' => "Desarrollo activamente mis habilidades de enseñanza digital.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 3
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Rara vez tengo tiempo para mejorar mis habilidades de enseñanza digital.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Mejoro mis habilidades a través de la reflexión y la experimentación.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Utilizo una variedad de recursos digitales para desarrollar mis habilidades docentes.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Discuto con colegas cómo utilizar las tecnologías digitales para innovar y mejorar la práctica educativa.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Ayudo a mis colegas a desarrollar estrategias para mejorar el uso de las tecnologías digitales en la enseñanza.",
            'score' => 4,
        ]);


        $question4 = Question::create([
            'test_id' => $test->id,
            'question' => "Participo en capacitaciones en línea cuando tengo la oportunidad. Por ejemplo: cursos online, MOOCs, webinars, congresos virtuales…",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 4
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "Esta es un área nueva que aún no he considerado.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "Todavía no, pero definitivamente estoy interesado.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "He participado en capacitaciones en línea una o dos veces.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "En varias ocasiones participé en capacitaciones en línea.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "Participo frecuentemente en todo tipo de capacitaciones en línea",
            'score' => 4,
        ]);


        $question5 = Question::create([
            'test_id' => $test->id,
            'question' => "Utilizo diferentes sitios web y estrategias de búsqueda para encontrar y seleccionar diferentes recursos digitales.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 5
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Rara vez uso Internet para buscar recursos.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Busco contenido relevante en diferentes sitios web y plataformas de recursos educativos.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Evalúo y selecciono recursos en función del aprendizaje de los estudiantes.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Comparo características utilizando una variedad de criterios relevantes, por ejemplo, confiabilidad, calidad, idoneidad, diseño, interactividad y atractivo.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Recomiendo recursos y estrategias de investigación a mis colegas.",
            'score' => 4,
        ]);


        $question6 = Question::create([
            'test_id' => $test->id,
            'question' => "Creo mis propios recursos digitales y adapto los recursos existentes en función de mis necesidades.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 6
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "No creo mis propios recursos digitales.",
            'score' => 0
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Creo materiales para clases usando una computadora, pero luego los imprimo.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Creo presentaciones digitales, pero no sé hacer mucho más que eso.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Creo diferentes tipos de recursos.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Organizo y adapto recursos complejos e interactivos.",
            'score' => 4,
        ]);


        $question7 = Question::create([
            'test_id' => $test->id,
            'question' => "Protejo eficazmente el contenido sensible. Por ejemplo: exámenes, calificaciones, datos personales de los estudiantes.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 7
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "No lo necesito porque la Institución se encarga de eso.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Evito almacenar datos personales electrónicamente.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Protejo con contraseña algunos datos personales.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Siempre protejo los archivos o datos personales con una contraseña.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Protejo los datos personales de forma integral, por ejemplo, combinando contraseñas difíciles de adivinar con cifrado y actualizaciones de software frecuentes.",
            'score' => 4,
        ]);


        $question8 = Question::create([
            'test_id' => $test->id,
            'question' => "Considero cuidadosamente cómo, cuándo y por qué utilizar tecnologías digitales en el aula para asegurar que agreguen valor al proceso de enseñanza y aprendizaje.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 8
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "No uso, o rara vez uso, tecnología en clase.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Hago un uso básico del equipamiento disponible, por ejemplo, pizarras digitales o proyectores.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Utilizo diversos recursos y herramientas digitales en la enseñanza.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Utilizo herramientas digitales para mejorar sistemáticamente la enseñanza.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Utilizo herramientas digitales para implementar estrategias pedagógicas innovadoras.",
            'score' => 4,
        ]);


        $question9 = Question::create([
            'test_id' => $test->id,
            'question' => "Superviso las actividades e interacciones de los estudiantes en los entornos colaborativos en línea que utilizamos.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 9
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "No utilizo entornos digitales con mis alumnos.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "No superviso la actividad de los estudiantes en los entornos en línea que uso.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "De vez en cuando reviso las discusiones de los estudiantes.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "Superviso y analizo periódicamente las actividades en línea de los estudiantes.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "Intervengo periódicamente con comentarios motivadores o correctivos.",
            'score' => 4,
        ]);


        $question10 = Question::create([
            'test_id' => $test->id,
            'question' => "Cuando mis estudiantes trabajan en grupos, utilizan tecnologías digitales para construir y documentar conocimientos.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 10
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Mis alumnos no trabajan en grupos.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "No me es posible integrar tecnologías durante el trabajo en grupo.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Animo a los estudiantes a trabajar en grupos para buscar información en línea o presentar sus resultados en formato digital.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Pido a los estudiantes que trabajan en grupos que utilicen Internet para buscar información o presentar sus resultados en formato digital.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Mis estudiantes intercambian evidencia y crean conocimiento juntos en un espacio colaborativo en línea.",
            'score' => 4,
        ]);


        $question11 = Question::create([
            'test_id' => $test->id,
            'question' => "Utilizo tecnologías digitales para permitir a los estudiantes planificar, documentar y supervisar su aprendizaje.Por ejemplo: cuestionario online de autoevaluación, e-portafolios para documentación y difusión, diarios/blogs online de reflexión…",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 11
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "No es posible en mi contexto laboral.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "Mis alumnos reflexionan sobre su aprendizaje, pero no con tecnologías digitales.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "A veces utilizo, por ejemplo, cuestionarios de autoevaluación.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "Utilizo una variedad de herramientas digitales para permitir que los estudiantes planifiquen, documenten o reflexionen sobre su aprendizaje.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "Integro diferentes herramientas digitales para planificar y monitorear el progreso de los estudiantes.",
            'score' => 4,
        ]);


        $question12 = Question::create([
            'test_id' => $test->id,
            'question' => "Utilizo herramientas de evaluación digital para monitorear el progreso de los estudiantes.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 12
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "No superviso el progreso de los estudiantes.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "Superviso periódicamente el progreso de los estudiantes, pero no a través de medios digitales.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "A veces uso una herramienta digital, por ejemplo un cuestionario, para monitorear el progreso de los estudiantes.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "Utilizo una variedad de herramientas digitales para monitorear el progreso de los estudiantes.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "Utilizo sistemáticamente una variedad de herramientas digitales para monitorear el progreso de los estudiantes.",
            'score' => 4,
        ]);


        $question13 = Question::create([
            'test_id' => $test->id,
            'question' => "Analizo todos los datos disponibles de los estudiantes para identificar eficazmente a aquellos que necesitan apoyo adicional.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 13
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "Estos datos no están disponibles y/o no es mi responsabilidad analizarlos.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "En parte, sólo miro datos académicamente relevantes, por ejemplo, el rendimiento y las clasificaciones.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "También considero datos sobre la actividad y el comportamiento de los estudiantes para identificar a aquellos que necesitan apoyo adicional.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "Reviso periódicamente la evidencia disponible para identificar a los estudiantes que necesitan apoyo adicional.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "Analizo sistemáticamente los datos e intervengo adecuadamente.",
            'score' => 4,
        ]);


        $question14 = Question::create([
            'test_id' => $test->id,
            'question' => "Utilizo tecnologías digitales para brindar retroalimentación efectiva.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 14
        ]);

        Option::create([
            'question_id' => $question14->id,
            'option' => "La retroalimentación no es necesaria en mi contexto laboral.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question14->id,
            'option' => "Proporciono retroalimentación a los estudiantes, pero no en formato digital.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question14->id,
            'option' => "En ocasiones utilizo herramientas digitales para proporcionar feedback, por ejemplo: puntuación automática en un cuestionario online o \"me gusta\" en entornos digitales.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question14->id,
            'option' => "Utilizo una variedad de herramientas digitales para brindar retroalimentación.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question14->id,
            'option' => "Utilizo sistemáticamente enfoques digitales para proporcionar retroalimentación.",
            'score' => 4,
        ]);


        $question15 = Question::create([
            'test_id' => $test->id,
            'question' => 'Cuando creo tareas digitales para estudiantes, considero y abordo posibles dificultades prácticas o técnicas. Por ejemplo: "acceso equitativo a dispositivos y recursos digitales, problemas de interoperabilidad y conversión, falta de habilidades digitales, ....".',
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 15
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "No creo tareas digitales.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "Mis alumnos no tienen ningún problema en utilizar la tecnología digital.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "Adapto la tarea para minimizar las dificultades.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "⁠Discuto posibles obstáculos con los estudiantes y propongo soluciones.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "⁠Permito la variedad, por ejemplo, adapto la tarea, analizo soluciones y ofrezco formas alternativas de completar la tarea.",
            'score' => 4,
        ]);


        $question16 = Question::create([
            'test_id' => $test->id,
            'question' => 'Utilizo tecnologías digitales para brindarles a los estudiantes oportunidades de aprendizaje personalizadas. Por ejemplo: "Les doy a los estudiantes diferentes tareas digitales para satisfacer sus necesidades de aprendizaje, preferencias e intereses individuales".',
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 16
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "En mi contexto de trabajo, a todos los estudiantes se les pide que realicen las mismas actividades, independientemente de su nivel.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Ofrezco a los estudiantes recomendaciones de recursos adicionales.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Ofrezco actividades digitales opcionales para estudiantes que están adelantados o atrasados.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Siempre que sea posible, uso tecnologías digitales para ofrecer oportunidades de aprendizaje diferenciadas.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Adapto sistemáticamente mi enseñanza para relacionarla con las necesidades, preferencias e intereses de los estudiantes.",
            'score' => 4,
        ]);


        $question17 = Question::create([
            'test_id' => $test->id,
            'question' => "Utilizo tecnologías digitales para que los estudiantes participen activamente en las clases.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 17
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "En mi contexto laboral, no es posible involucrar activamente a los estudiantes en clase.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Involucro activamente a los estudiantes en clase, pero no con tecnologías digitales.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Cuando enseño, uso estímulos motivadores, por ejemplo vídeos y animaciones.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Mis alumnos interactúan con medios digitales en mis clases, por ejemplo, hojas de cálculo, juegos y cuestionarios.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Mis estudiantes utilizan tecnologías digitales para investigar, discutir y crear conocimiento sistemáticamente.",
            'score' => 4,
        ]);


        $question18 = Question::create([
            'test_id' => $test->id,
            'question' => "Enseño a mis estudiantes cómo evaluar la confiabilidad de la información, identificar inexactitudes e información distorsionada.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 18
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Esto no es posible en mi curso o contexto laboral.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "De vez en cuando les recuerdo a los estudiantes que no toda la información en línea es confiable.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Enseño a los estudiantes como discernir fuentes confiables y no confiables.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Discuto con los estudiantes cómo comprobar la exactitud de la información.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Hemos discutido extensamente cómo se crea la información y cómo puede distorsionarse.",
            'score' => 4,
        ]);


        $question19 = Question::create([
            'test_id' => $test->id,
            'question' => "Preparo tareas que requieren que los estudiantes utilicen medios digitales para comunicarse y colaborar entre sí o con una audiencia externa.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 19
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Esto no es posible en mi curso o contexto laboral.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Sólo en raras ocasiones necesito que los estudiantes se comuniquen y colaboren en línea.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Mis estudiantes utilizan la comunicación y la colaboración digitales, especialmente entre ellos.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Mis estudiantes utilizan medios digitales para comunicarse y colaborar entre sí y con una audiencia externa.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Preparo sistemáticamente tareas que permitan a los estudiantes ampliar lentamente sus habilidades.",
            'score' => 4,
        ]);


        $question20 = Question::create([
            'test_id' => $test->id,
            'question' => 'Preparo tareas que requieren que los estudiantes creen contenido digital. Por ejemplo: "vídeos, audios, fotos, presentaciones digitales, blogs, wikis…".',
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 20
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "Esto no es posible en mi curso o contexto laboral.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "Esto es difícil de implementar con mis estudiantes.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "A veces por diversión y motivación.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "⁠Mis estudiantes crean contenido digital como parte integral de su estudio.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "Esta es una parte integral de tu aprendizaje y aumento sistemáticamente el nivel de dificultad para desarrollar aún más tus habilidades.",
            'score' => 4,
        ]);


        $question21 = Question::create([
            'test_id' => $test->id,
            'question' => "Enseño a los estudiantes cómo utilizar la tecnología digital de forma segura y responsable.",
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 21
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "Esto no es posible en mi curso o contexto laboral.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "⁠Aconsejo a los estudiantes que tengan cuidado al compartir información personal en línea.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "Explico las reglas básicas para actuar de forma segura y responsable en entornos online.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "A menudo experimentamos con soluciones a problemas tecnológicos.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "Desarrollo sistemáticamente el uso de reglas sociales en los diferentes entornos digitales que utilizamos.",
            'score' => 4,
        ]);


        $question22 = Question::create([
            'test_id' => $test->id,
            'question' => 'Animo a los estudiantes a utilizar las tecnologías digitales de forma creativa para resolver problemas del mundo real. Por ejemplo: "superar obstáculos o desafíos emergentes en el proceso de aprendizaje".',
            'area_id' => $area->id,
            'factor_id' => $factor->id,
            'order' => 22
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "Esto no es posible en mi curso o contexto laboral.",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "Rara vez tengo la oportunidad de promover la resolución de problemas digitales de los estudiantes.",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "De vez en cuando, cuando surge una oportunidad.",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "A menudo experimentamos con soluciones tecnológicas a los problemas.",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "Integro sistemáticamente oportunidades para la resolución creativa de problemas digitales.",
            'score' => 4,
        ]);



    }
}
