<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class TestCompetencyLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'name',
        'code',
        'min_score',
        'max_score',
        'description',
    ];

    protected $casts = [
        'min_score' => 'integer',
        'max_score' => 'integer',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    
    public static function getLevelForScore(int $testId, int $score): ?self
    {
        $cacheKey = "test_{$testId}_competency_level_score_{$score}";

        return Cache::remember($cacheKey, 3600, function () use ($testId, $score) {
            return static::where('test_id', $testId)
                ->where('min_score', '<=', $score)
                ->where('max_score', '>=', $score)
                ->first();
        });
    }

    public static function clearCacheForTest(int $testId): void
    {
        $levels = static::where('test_id', $testId)->get();
        foreach ($levels as $level) {
            for ($i = $level->min_score; $i <= $level->max_score; $i++) {
                Cache::forget("test_{$testId}_competency_level_score_{$i}");
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(fn($level) => self::clearCacheForTest($level->test_id));
        static::deleted(fn($level) => self::clearCacheForTest($level->test_id));
    }


    public static function initializeLevels(): void
    {
        //inicializar la variable test dame el codigo
        $test = Test::where('name', 'Evaluación de Competencias Digitales Docentes')->first();

        $levels = [
            [
                'test_id'=> $test->id,
                'name' => 'Novato',
                'code' => 'A1',
                'min_score' => 0,
                'max_score' => 19,
                'description' => 'Muy poca experiencia y contacto con la tecnología educativa. Necesita orientación continua para mejorar su nivel competencial digital docente. '

            ],
            [
                'test_id'=> $test->id,
                'name' => 'Explorador',
                'code' => 'A2',
                'min_score' => 20,
                'max_score' => 33,
                'description' => 'Poco contacto con la tecnología educativa. No ha desarrollado estrategias específicas para incluir las TIC en el aula. Necesita orientación externa para mejorar su nivel competencial digital docente.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Integrador',
                'code' => 'B1',
                'min_score' => 34,
                'max_score' => 49,
                'description' => 'Experimenta con la tecnología educativa y reflexiona sobre su idoneidad para los distintos contextos educativos.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Experto',
                'code' => 'B2',
                'min_score' => 50,
                'max_score' => 65,
                'description' => 'Utiliza una amplia gama de tecnologías educativas con seguridad, confianza y creatividad. Busca la mejora continua de sus prácticas docentes.'
            ],
            [
                'test_id'=> $test->id,

                'name' => 'Líder',
                'code' => 'C1',
                'min_score' => 66,
                'max_score' => 80,
                'description' => 'Capaz de adaptar a sus necesidades los distintos recursos, estrategias y conocimientos a su alcance. Es una fuente de inspiración para otros docentes.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Pionero',
                'code' => 'C2',
                'min_score' => 81,
                'max_score' => 100,
                'description' => 'Cuestiona las prácticas digitales y pedagógicas contemporáneas, de las que ellos mismos son expertos. Lideran la innovación con TIC y son un modelo a seguir para otros docentes'
            ],
        ];

        foreach ($levels as $level) {
            static::firstOrCreate(
                ['code' => $level['code']],
                $level
            );
        }

    
    } 

    public static function initializeLevelsIA(): void
    {
        $test = Test::where('name', 'Test Inteligencia Artificial')->first();

        $levels = [
            [
                'test_id'=> $test->id,
                'name' => 'Novato',
                'code' => 'A1',
                'min_score' => 0,
                'max_score' =>32,
                'description' => 'Entiende lo básico de cómo la lA puede ser usada para el desarrollo profesional y la comunicación. - Comienza a interactuar con herramientas de lA para tareas simples como respuestas automáticas de correo
electrónico o análisis de datos básicos para la gestión del aula. - Conciencia del potencial de la lA en el desarrollo profesional y la red de contactos, pero con aplicación práctica
limitada.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Explorador',
                'code' => 'A2',
                'min_score' => 33,
                'max_score' => 52,
                'description' => 'Explora activamente diferentes herramientas de lA para la comunicación y el desarrollo profesional. - Comienza a integrar la lA en tareas profesionales rutinarias, como el uso de lA para organizar reuniones o la
interpretación básica de datos. Participa en comunidades de aprendizaje profesional discutiendo sobre la lA en la educación, compartiendo
experiencias iniciales y aprendiendo de los compañeros.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Integrador',
                'code' => 'B1',
                'min_score' => 53,
                'max_score' => 76,
                'description' => 'Usa regularmente herramientas de lA para una gama de actividades profesionales, incluido el análisis de datos
avanzado y la optimización de la comunicación.
- Integra la lA en planes de desarrollo profesional, buscando formación y recursos enfocados en lA.
- Colabora utilizando herramientas de lA, demostrando uso efectivo en proyectos de equipo o iniciativas colaborativas.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Experto',
                'code' => 'B2',
                'min_score' => 77,
                'max_score' => 104,
                'description' => 'Navega expertamente una variedad de herramientas de lA para fines profesionales avanzados, como análisis
predictivos para el rendimiento estudiantil. Dirige sesiones de desarrollo profesional o talleres sobre lA para colegas, compartiendo experiencia y mejores
prácticas. - Utiliza innovadoramente la lA para el crecimiento personal y organizacional, contribuyendo a la comunidad educativa
más amplia a través de iniciativas mejoradas por IA.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Líder',
                'code' => 'C1',
                'min_score' => 105,
                'max_score' => 132,
                'description' => 'Lidera la adopción de tecnologías de lA de vanguardia en entornos profesionales.
Da forma a políticas y prácticas organizacionales en torno al uso de la lA en el compromiso profesional. Orienta a otros en la integración de la lA en prácticas profesionales, impulsando el cambio y la innovación dentro de
la comunidad educativa.'
            ],
            [
                'test_id'=> $test->id,
                'name' => 'Pionero',
                'code' => 'C2',
                'min_score' => 133,
                'max_score' => 150,
                'description' => 'Avanza en el campo contribuyendo con ideas originales o investigación sobre el uso de la lA en el desarrollo
profesional y la comunicación. Lidera proyectos o iniciativas importantes que transforman el compromiso profesional a través de la lA a un nivel
sistémico. Es un experto reconocido y líder de pensamiento en el uso de la lA en entornos educativos, influenciando políticas,
prácticas y futuros desarrollos.'
            ],
        ];

        foreach ($levels as $level) {
            static::firstOrCreate(
                ['code' => $level['code'], 'test_id' => $test->id],
                $level
            );
        }
    }

}