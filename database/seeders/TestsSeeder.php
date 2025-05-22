<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Question;
use App\Models\Option;

class TestsSeeder extends Seeder
{
    public function run()
    {
        $test = Test::create([]);


        $question1 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Utilizo sistemáticamente diferentes canales de comunicación para mejorar la comunicación con estudiantes y colegas.   Por ejemplo: correos electrónicos, blogs, sitio web de la institución, aplicaciones…",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Rara vez uso canales de comunicación digitales.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Utilizo canales de comunicación básicos, por ejemplo el correo electrónico.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Combino diferentes canales de comunicación, por ejemplo el correo electrónico, el blog de la clase o el sitio web de la institución.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Selecciono, ajusto y combino sistemáticamente diferentes soluciones digitales para comunicarme de manera efectiva.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Reflexiono, discuto y desarrollo mis estrategias de comunicación de forma proactiva.",
            'is_correct' => true,
        ]);


        $question2 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Utilizo tecnologías digitales para trabajar con colegas dentro y fuera de mi institución.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Rara vez tengo la oportunidad de colaborar con otros colegas.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "A veces intercambio materiales con colegas, por ejemplo por correo electrónico.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Entre colegas, trabajamos juntos en entornos colaborativos o utilizamos archivos/unidades compartidos.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Intercambio ideas y materiales, también con colegas fuera de mi institución, por ejemplo en una red profesional en línea o en un espacio colaborativo en línea.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Creo materiales junto con otros colegas en una red online de profesionales de diferentes instituciones.",
            'is_correct' => true,
        ]);


        $question3 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Desarrollo activamente mis habilidades de enseñanza digital.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Rara vez tengo tiempo para mejorar mis habilidades de enseñanza digital.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Mejoro mis habilidades a través de la reflexión y la experimentación.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Utilizo una variedad de recursos digitales para desarrollar mis habilidades docentes.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Discuto con colegas cómo utilizar las tecnologías digitales para innovar y mejorar la práctica educativa.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Ayudo a mis colegas a desarrollar estrategias para mejorar el uso de las tecnologías digitales en la enseñanza.",
            'is_correct' => true,
        ]);


        $question4 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Participo en capacitaciones en línea cuando tengo la oportunidad. Por ejemplo: cursos online, MOOCs, webinars, congresos virtuales…",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "Esta es un área nueva que aún no he considerado.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "Todavía no, pero definitivamente estoy interesado.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "He participado en capacitaciones en línea una o dos veces.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "En varias ocasiones participé en capacitaciones en línea.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "Participo frecuentemente en todo tipo de capacitaciones en línea",
            'is_correct' => true,
        ]);


        $question5 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Utilizo diferentes sitios web y estrategias de búsqueda para encontrar y seleccionar diferentes recursos digitales.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Rara vez uso Internet para buscar recursos.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Busco contenido relevante en diferentes sitios web y plataformas de recursos educativos.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Evalúo y selecciono recursos en función del aprendizaje de los estudiantes.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Comparo características utilizando una variedad de criterios relevantes, por ejemplo, confiabilidad, calidad, idoneidad, diseño, interactividad y atractivo.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Recomiendo recursos y estrategias de investigación a mis colegas.",
            'is_correct' => true,
        ]);


        $question6 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Creo mis propios recursos digitales y adapto los recursos existentes en función de mis necesidades.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "No creo mis propios recursos digitales.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Creo materiales para clases usando una computadora, pero luego los imprimo.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Creo presentaciones digitales, pero no sé hacer mucho más que eso.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Creo diferentes tipos de recursos.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Organizo y adapto recursos complejos e interactivos.",
            'is_correct' => true,
        ]);


        $question7 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Protejo eficazmente el contenido sensible. Por ejemplo: exámenes, calificaciones, datos personales de los estudiantes.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "No lo necesito porque la Institución se encarga de eso.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Evito almacenar datos personales electrónicamente.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Protejo con contraseña algunos datos personales.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Siempre protejo los archivos o datos personales con una contraseña.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Protejo los datos personales de forma integral, por ejemplo, combinando contraseñas difíciles de adivinar con cifrado y actualizaciones de software frecuentes.",
            'is_correct' => true,
        ]);


        $question8 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Considero cuidadosamente cómo, cuándo y por qué utilizar tecnologías digitales en el aula para asegurar que agreguen valor al proceso de enseñanza y aprendizaje.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "No uso, o rara vez uso, tecnología en clase.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Hago un uso básico del equipamiento disponible, por ejemplo, pizarras digitales o proyectores.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Utilizo diversos recursos y herramientas digitales en la enseñanza.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Utilizo herramientas digitales para mejorar sistemáticamente la enseñanza.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Utilizo herramientas digitales para implementar estrategias pedagógicas innovadoras.",
            'is_correct' => true,
        ]);


        $question9 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Superviso las actividades e interacciones de los estudiantes en los entornos colaborativos en línea que utilizamos.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "No utilizo entornos digitales con mis alumnos.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "No superviso la actividad de los estudiantes en los entornos en línea que uso.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "De vez en cuando reviso las discusiones de los estudiantes.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "Superviso y analizo periódicamente las actividades en línea de los estudiantes.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "Intervengo periódicamente con comentarios motivadores o correctivos.",
            'is_correct' => true,
        ]);


        $question10 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Cuando mis estudiantes trabajan en grupos, utilizan tecnologías digitales para construir y documentar conocimientos.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Mis alumnos no trabajan en grupos.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "No me es posible integrar tecnologías durante el trabajo en grupo.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Animo a los estudiantes a trabajar en grupos para buscar información en línea o presentar sus resultados en formato digital.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Pido a los estudiantes que trabajan en grupos que utilicen Internet para buscar información o presentar sus resultados en formato digital.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Mis estudiantes intercambian evidencia y crean conocimiento juntos en un espacio colaborativo en línea.",
            'is_correct' => true,
        ]);


        $question11 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Utilizo tecnologías digitales para permitir a los estudiantes planificar, documentar y supervisar su aprendizaje.Por ejemplo: cuestionario online de autoevaluación, e-portafolios para documentación y difusión, diarios/blogs online de reflexión…",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "No es posible en mi contexto laboral.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "Mis alumnos reflexionan sobre su aprendizaje, pero no con tecnologías digitales.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "A veces utilizo, por ejemplo, cuestionarios de autoevaluación.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "Utilizo una variedad de herramientas digitales para permitir que los estudiantes planifiquen, documenten o reflexionen sobre su aprendizaje.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "Integro diferentes herramientas digitales para planificar y monitorear el progreso de los estudiantes.",
            'is_correct' => true,
        ]);


        $question12 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Utilizo herramientas de evaluación digital para monitorear el progreso de los estudiantes.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "No superviso el progreso de los estudiantes.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "Superviso periódicamente el progreso de los estudiantes, pero no a través de medios digitales.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "A veces uso una herramienta digital, por ejemplo un cuestionario, para monitorear el progreso de los estudiantes.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "Utilizo una variedad de herramientas digitales para monitorear el progreso de los estudiantes.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "Utilizo sistemáticamente una variedad de herramientas digitales para monitorear el progreso de los estudiantes.",
            'is_correct' => true,
        ]);


        $question13 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Analizo todos los datos disponibles de los estudiantes para identificar eficazmente a aquellos que necesitan apoyo adicional.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "Estos datos no están disponibles y/o no es mi responsabilidad analizarlos.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "En parte, sólo miro datos académicamente relevantes, por ejemplo, el rendimiento y las clasificaciones.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "También considero datos sobre la actividad y el comportamiento de los estudiantes para identificar a aquellos que necesitan apoyo adicional.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "Reviso periódicamente la evidencia disponible para identificar a los estudiantes que necesitan apoyo adicional.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "Analizo sistemáticamente los datos e intervengo adecuadamente.",
            'is_correct' => true,
        ]);


        $question14 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Utilizo tecnologías digitales para brindar retroalimentación efectiva.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question14->id,
            'option' => "La retroalimentación no es necesaria en mi contexto laboral.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question14->id,
            'option' => "Proporciono retroalimentación a los estudiantes, pero no en formato digital.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question14->id,
            'option' => "En ocasiones utilizo herramientas digitales para proporcionar feedback, por ejemplo: puntuación automática en un cuestionario online o \"me gusta\" en entornos digitales.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question14->id,
            'option' => "Utilizo una variedad de herramientas digitales para brindar retroalimentación.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question14->id,
            'option' => "Utilizo sistemáticamente enfoques digitales para proporcionar retroalimentación.",
            'is_correct' => true,
        ]);


        $question15 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Cuando creo tareas digitales para estudiantes, considero y abordo posibles dificultades prácticas o técnicas. Por ejemplo: “acceso equitativo a dispositivos y recursos digitales, problemas de interoperabilidad y conversión, falta de habilidades digitales, ....”.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "No creo tareas digitales.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "Mis alumnos no tienen ningún problema en utilizar la tecnología digital.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "Adapto la tarea para minimizar las dificultades.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "⁠Discuto posibles obstáculos con los estudiantes y propongo soluciones.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "⁠Permito la variedad, por ejemplo, adapto la tarea, analizo soluciones y ofrezco formas alternativas de completar la tarea.",
            'is_correct' => true,
        ]);


        $question16 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Utilizo tecnologías digitales para brindarles a los estudiantes oportunidades de aprendizaje personalizadas. Por ejemplo: \"Les doy a los estudiantes diferentes tareas digitales para satisfacer sus necesidades de aprendizaje, preferencias e intereses individuales\".",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "En mi contexto de trabajo, a todos los estudiantes se les pide que realicen las mismas actividades, independientemente de su nivel.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Ofrezco a los estudiantes recomendaciones de recursos adicionales.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Ofrezco actividades digitales opcionales para estudiantes que están adelantados o atrasados.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Siempre que sea posible, uso tecnologías digitales para ofrecer oportunidades de aprendizaje diferenciadas.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Adapto sistemáticamente mi enseñanza para relacionarla con las necesidades, preferencias e intereses de los estudiantes.",
            'is_correct' => true,
        ]);


        $question17 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Utilizo tecnologías digitales para que los estudiantes participen activamente en las clases.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "En mi contexto laboral, no es posible involucrar activamente a los estudiantes en clase.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Involucro activamente a los estudiantes en clase, pero no con tecnologías digitales.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Cuando enseño, uso estímulos motivadores, por ejemplo vídeos y animaciones.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Mis alumnos interactúan con medios digitales en mis clases, por ejemplo, hojas de cálculo, juegos y cuestionarios.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Mis estudiantes utilizan tecnologías digitales para investigar, discutir y crear conocimiento sistemáticamente.",
            'is_correct' => true,
        ]);


        $question18 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Enseño a mis estudiantes cómo evaluar la confiabilidad de la información, identificar inexactitudes e información distorsionada.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Esto no es posible en mi curso o contexto laboral.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "⁠De vez en cuando les recuerdo a los estudiantes que no toda la información en línea es confiable.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Enseño a los estudiantes como discernir fuentes confiables y no confiables.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Discuto con los estudiantes cómo comprobar la exactitud de la información.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Hemos discutido extensamente cómo se crea la información y cómo puede distorsionarse.",
            'is_correct' => true,
        ]);


        $question19 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Preparo tareas que requieren que los estudiantes utilicen medios digitales para comunicarse y colaborar entre sí o con una audiencia externa.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Esto no es posible en mi curso o contexto laboral.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Sólo en raras ocasiones necesito que los estudiantes se comuniquen y colaboren en línea.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Mis estudiantes utilizan la comunicación y la colaboración digitales, especialmente entre ellos.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Mis estudiantes utilizan medios digitales para comunicarse y colaborar entre sí y con una audiencia externa.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Preparo sistemáticamente tareas que permitan a los estudiantes ampliar lentamente sus habilidades.",
            'is_correct' => true,
        ]);


        $question20 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Preparo tareas que requieren que los estudiantes creen contenido digital. Por ejemplo: “vídeos, audios, fotos, presentaciones digitales, blogs, wikis…”.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "Esto no es posible en mi curso o contexto laboral.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "Esto es difícil de implementar con mis estudiantes.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "A veces por diversión y motivación.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "⁠Mis estudiantes crean contenido digital como parte integral de su estudio.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "Esta es una parte integral de tu aprendizaje y aumento sistemáticamente el nivel de dificultad para desarrollar aún más tus habilidades.",
            'is_correct' => true,
        ]);


        $question21 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Enseño a los estudiantes cómo utilizar la tecnología digital de forma segura y responsable.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "Esto no es posible en mi curso o contexto laboral.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "⁠Aconsejo a los estudiantes que tengan cuidado al compartir información personal en línea.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "Explico las reglas básicas para actuar de forma segura y responsable en entornos online.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "A menudo experimentamos con soluciones a problemas tecnológicos.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "Desarrollo sistemáticamente el uso de reglas sociales en los diferentes entornos digitales que utilizamos.",
            'is_correct' => true,
        ]);


        $question22 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Animo a los estudiantes a utilizar las tecnologías digitales de forma creativa para resolver problemas del mundo real. Por ejemplo: “superar obstáculos o desafíos emergentes en el proceso de aprendizaje”.",
            'category_id' => 1
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "Esto no es posible en mi curso o contexto laboral.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "Rara vez tengo la oportunidad de promover la resolución de problemas digitales de los estudiantes.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "De vez en cuando, cuando surge una oportunidad.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "A menudo experimentamos con soluciones tecnológicas a los problemas.",
            'is_correct' => false,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "⁠Integro sistemáticamente oportunidades para la resolución creativa de problemas digitales.",
            'is_correct' => true,
        ]);


        $question23 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Sé cómo encontrar y utilizar licencias abiertas en recursos educativos digitales abiertos REDA.",
            'category_id' => 1
        ]);


        $question24 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Adopto prácticas educativas abiertas en mi práctica docente para hacerla más inclusiva.",
            'category_id' => 1
        ]);


        $question25 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Siempre que sea posible, publico mi investigación en revistas científicas abiertas y mis datos de investigación.",
            'category_id' => 1
        ]);


        $question26 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Cual es tu sexo/género?",
            'category_id' => 1
        ]);


        $question27 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Cuántos años tiene?",
            'category_id' => 1
        ]);


        $question28 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Incluyendo este año escolar, ¿cuántos años lleva usted trabajando como docente?",
            'category_id' => 1
        ]);


        $question29 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿En qué nivel de educación trabajas principalmente?",
            'category_id' => 1
        ]);


        $question30 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Tipo de institución en la que trabaja principalmente?",
            'category_id' => 1
        ]);


        $question31 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Cuál es su campus de operaciones?",
            'category_id' => 1
        ]);


        $question32 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Cual es tu titulo?",
            'category_id' => 1
        ]);


        $question33 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿En qué tipo de contrataciòn tiene?",
            'category_id' => 1
        ]);


        $question34 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿En qué áreas del conocimiento docente trabaja usted predominantemente como docente?",
            'category_id' => 1
        ]);


        $question35 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿En qué área del conocimiento estás formado?",
            'category_id' => 1
        ]);


        $question36 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Cuál es el perfil principal de sus estudiantes?",
            'category_id' => 1
        ]);


        $question37 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Cómo clasificas, en porcentaje (%), el uso de tecnologías y herramientas digitales en los últimos 3 meses en tus prácticas docentes?",
            'category_id' => 1
        ]);


        $question38 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Cuánto tiempo llevas utilizando tecnologías digitales en tus clases?",
            'category_id' => 1
        ]);


        $question39 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Qué recursos y/o herramientas digitales han sido utilizados por usted y/o sus estudiantes para enseñar y aprender? Son varias respuestas",
            'category_id' => 1
        ]);


        $question40 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Qué dispositivos tienes en casa que estén disponibles para tu uso (no solo para trabajar)? Son posibles varias respuestas. Son posibles varias respuestas",
            'category_id' => 1
        ]);


        $question41 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Cómo es tu acceso a internet en casa?",
            'category_id' => 1
        ]);


        $question42 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Cuál es la edad media de tus alumnos? Son posibles varias respuestas",
            'category_id' => 1
        ]);


        $question43 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿En que tipo de estudios se encuentran sus estudiantes?",
            'category_id' => 1
        ]);


        $question44 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Cómo te describirías a ti mismo y tu uso personal de las tecnologías digitales?",
            'category_id' => 1
        ]);


        $question45 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿En qué medida su contexto de trabajo se ajusta a los siguientes criterios?",
            'category_id' => 1
        ]);


        $question46 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "La pandemia de COVID-19 trajo consigo la necesidad de adaptaciones metodológicas en el proceso de enseñanza y aprendizaje para que las actividades pudieran desarrollarse durante el período de aislamiento social. ¿En qué medida considera usted que su institución ha trabajado y/o está trabajando en esta adaptación?",
            'category_id' => 1
        ]);


        $question47 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "¿Consideras que tu experiencia docente en el contexto de la pandemia de COVID-19 ha mejorado tu nivel de competencia digital docente?",
            'category_id' => 1
        ]);


        $question48 = Question::create([
            'test_id' => $test->id,
            'pregunta' => "Ahora bien, después de responder el cuestionario, ¿cómo valora usted su competencia digital? Asigna un nivel del A1 al C2, donde A1 es el nivel más bajo y C2 es el más alto. Probablemente soy un:",
            'category_id' => 1
        ]);
    }
}
